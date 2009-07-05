if(typeof SWFUpload === "undefined")
{
  throw "Swfupload must be installed to get the queue working";
}

/**
 * Array.indexOf workaround
 */
if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length >>> 0;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

// Mixins for accessing the UploadProgress instance of a SWFUpload Object

/**
 * @var _uploadProgressInstance:SWFUploadProgress
 */
SWFUpload.prototype._uploadProgressInstance = null;

/**
 * @param upload_progress_instance:SWFUploadProgress
 */
SWFUpload.prototype.setUploadProgress = function (upload_progress_instance)
{
  this._uploadProgressInstance = upload_progress_instance;
}

/**
 * @return upload_progress_instance:SWFUploadProgress
 */
SWFUpload.prototype.getUploadProgress = function()
{
  return this._uploadProgressInstance;
}

var OS_FLAGS = {
  LINUX:    'linux',
  WINDOWS:  'windows',
  UNIX:     'unix',
  MAC:      'mac',
  UNKNOWN:  'unknown'
};
var swfu_widget =
{
  HTTP_STATUS: {
    100 : 'Continue',
    101 : 'Switching Protocols',
    200 : 'OK',
    201 : 'Created',
    202 : 'Accepted',
    203 : 'Non-Authoritative Information',
    204 : 'No Content',
    205 : 'Reset Content',
    206 : 'Partial Content',
    300 : 'Multiple Choices',
    301 : 'Moved Permanently',
    302 : 'Found',
    303 : 'See Other',
    304 : 'Not Modified',
    305 : 'Use Proxy',
    306 : '(Unused)',
    307 : 'Temporary Redirect',
    400 : 'Bad Request',
    401 : 'Unauthorized',
    402 : 'Payment Required',
    403 : 'Forbidden',
    404 : 'Not Found',
    405 : 'Method Not Allowed',
    406 : 'Not Acceptable',
    407 : 'Proxy Authentication Required',
    408 : 'Request Timeout',
    409 : 'Conflict',
    410 : 'Gone',
    411 : 'Length Required',
    412 : 'Precondition Failed',
    413 : 'Request Entity Too Large',
    414 : 'Request-URI Too Long',
    415 : 'Unsupported Media Type',
    416 : 'Requested Range Not Satisfiable',
    417 : 'Expectation Failed',
    500 : 'Internal Server Error',
    501 : 'Not Implemented',
    502 : 'Bad Gateway',
    503 : 'Service Unavailable',
    504 : 'Gateway Timeout',
    505 : 'HTTP Version Not Supported'
  },
  OS: function()
  {
    var os=OS_FLAGS.UNKNOWN;
    if (navigator.appVersion.indexOf("Win")!=-1)    os=OS_FLAGS.WINDOWS;
    if (navigator.appVersion.indexOf("Mac")!=-1)    os=OS_FLAGS.MAC;
    if (navigator.appVersion.indexOf("X11")!=-1)    os=OS_FLAGS.UNIX;
    if (navigator.appVersion.indexOf("Linux")!=-1)  os=OS_FLAGS.LINUX;
    return os;
  }(),
  getPosOffset: function(element)
  {
    var left=0;
    var top=0;
    var p=element;
    while(true)
    {
      left += p.offsetLeft;
      top  += p.offsetTop;
      p=p.offsetParent;
      if(!p) return {'0' : left, 'left' : left, '1' : top, 'top' : top};
    }
  },
  size2String: function(size)
  {
    if(size<1000)
      return size + "&nbsp;Byte";
    else if(size < 1000000)
      return Math.round(size/1000,2) + "&nbsp;Kb";
    else if(size < 1000000000)
      return Math.round(size/1000000, 2) + "&nbsp;MB";
    else if(size < 1000000000000)
      return Math.round(size/1000000000, 2) + "&nbsp;GB";
  },
  mixin: function(/*arg1, arg2, argn*/)
  {
    var tgt = arguments[0];
    for(var i=1; len=arguments.length, i<len; i++)
    {
      for (var j in arguments[i])
      {
        tgt[j] = arguments[i][j];
      }
    }
    return tgt;
  },
  addClassName : function(element, class_name)
  {
    var class_names = element.className.split(' ');
    if(class_names.indexOf(class_name) === -1)
      class_names.push(class_name);
    element.className = class_names.join(' ');
    return element;
  },
  removeClassName: function(element, class_name)
  {
    var class_names = element.className.split(' ');
    if(class_names.indexOf(class_name) !== -1)
      delete class_names[class_names.indexOf(class_name)];
    element.className = class_names.join(' ');
    return element;
  },
  addObserver : function(subject, event_name, fn, thisObj)
  {
    if(thisObj)
      fn.___thisObj = thisObj;
    if(!subject._observers)
      subject._observers = {};
    if(!subject._observers[event_name])
      subject._observers[event_name] = [];
    subject._observers[event_name].push(fn);
  },
  removeObserver: function (subject, event_name, fn)
  {
    if(!subject._observers)return;
    if(subject._observers[event_name])
    delete subject._observers[event_name][subject._observers[event_name].indexOf(fn)];
  },
  fire: function(subject, event_name, eventObj)
  {
    if(!subject._observers) return;
    if(subject._observers[event_name])
    {
      for(var i=0; len=subject._observers[event_name].length, i<len; i++)
      {
        var thisObj = subject._observers[event_name][i].___thisObj ? subject._observers[event_name][i].___thisObj : subject;
        subject._observers[event_name][i].apply(thisObj, [eventObj, event_name, subject]);
      }
    }
  },
  addEventListener : function(obj, evType, fn, useCapture)
  {
    if (obj.addEventListener)
    {
      obj.addEventListener(evType, fn, useCapture);
      return true;
    } else if (obj.attachEvent){
      var r = obj.attachEvent("on"+evType, fn);
      return r;
    } else {
      throw "Handler could not be set.";
    }
    return false;
  },

  removeEventListener : function(obj, evType, fn, useCapture, context)
  {
    if (obj.removeEventListener){
      obj.removeEventListener(evType, fn, useCapture);
      return true;
    } else if (obj.detachEvent){
      var r = obj.detachEvent("on"+evType, fn);
      return r;
    } else {
      throw "Handler could not be removed";
    }
    return false;
  },

  Event :
  {
    preventDefault : function (e)
    {
      if (!e) var e = window.event;
      if(!e) throw "No event present that could be stopped."
      e.cancelBubble = true;
      if (e.preventDefault) e.preventDefault();
    },
    stopPropagation: function(e)
    {
      if (!e) var e = window.event;
      if(!e) throw "No event present that could be stopped."
      e.cancelBubble = true;
      if (e.stopPropagation) e.stopPropagation();
    }
  },

  FormSerializer: function(form)
  {
    this.form = form;
  },
  
  handlers:
  {
    onLoad: function()
    {
      
    },
    onFileDialogStart: function()
    {
      swfu_widget.fire(swfu_widget.handlers, 'file_dialog_start');
    },

    /**
     * @param file:object
     */
    onFileQueued: function(file)
    {
      swfu_widget.fire(swfu_widget.handlers, 'file_queued', {file:file})
    },
    
    /**
     * @param file:object
     * @param error:integer,
     * @param message:string
     */
    onFileQueueError: function(file, error_code, message)
    {
      swfu_widget.fire(swfu_widget.handlers, 'file_queue_error', {file:file, error_code:error_code, message:message})
    },

    /**
     * @param number_of_files_selected:integer
     * @param number_of_files_queued:integer
     * @param total_number_of_files_in_queue:integer
     */
    onFileDialogComplete: function(number_of_files_selected, number_of_files_queued, total_number_of_files_in_queue)
    {
      swfu_widget.fire(swfu_widget.handlers, 'file_dialog_complete',
      {
        number_of_files_selected:number_of_files_selected,
        number_of_files_queued:number_of_files_queued,
        total_number_of_files_in_queue:total_number_of_files_in_queue
      });
    },

    /**
     * @param file:object
     */
    onUploadStart: function(file)
    {
      swfu_widget.fire(swfu_widget.handlers, 'upload_start',{file: file});
    },

    /**
     * @param file:object
     * @param bytes_complete:integer
     * @param total_bytes:integer
     */
    onUploadProgress: function(file, bytes_complete, total_bytes)
    {
      swfu_widget.fire(swfu_widget.handlers, 'upload_progress', {
        file: file,
        bytes_complete: bytes_complete,
        total_bytes: total_bytes
      })
    },

    /**
     * @param file:object
     * @param error_code:integer
     * @param message:string
     */
    onUploadError: function(file, error_code, message)
    {
      swfu_widget.fire(swfu_widget.handlers, 'upload_error', {
        file: file,
        error_code: error_code,
        message: message
      });
    },

    /**
     * uploadSuccess is fired when the entire upload has been transmitted and
     * the server returns a HTTP 200 status code. Any data outputted by the server
     * is available in the server data parameter.
     *
     * Due to some bugs in the Flash Player the server response may not be acknowledged
     * and no uploadSuccess event is fired by Flash. In this case the assume_success_timeout
     * setting is checked to see if enough time has passed to fire uploadSuccess anyway. In
     * this case the received response parameter will be false.
     *
     * The http_success setting allows uploadSuccess to be fired for HTTP status
     * codes other than 200. In this case no server data is available from the Flash Player.
     *
     * At this point the upload is not yet complete. Another upload cannot be started from uploadSuccess.
     * 
     * @param file:object
     * @param server_data:string
     * @param recieved_response:string
     */
    onUploadSuccess: function(file, server_data, received_response)
    {
      swfu_widget.fire(swfu_widget.handlers, 'upload_success', {
        file: file,
        server_data: server_data,
        received_response: received_response
      });
    },

    /**
     * Queue plugin: If false is returned from uploadComplete then the queue upload is stopped.
		 * If false is not returned (strict comparison) then the queue upload is continued.
     *
     * @param file:object
     */
    onUploadComplete: function(file)
    {
      swfu_widget.fire(swfu_widget.handlers, 'upload_complete', {
        file:file
      });
    },

    /**
     * Queue Plugin: Adds a QueueComplete event that is fired when all the queued files have finished uploading.
		 * Set the event handler with the queue_complete_handler setting.
     */
    onQueueComplete: function()
    {
      swfu_widget.fire(swfu_widget.handlers, 'queue_complete');
    },

    /**
     * The swfuploadPreLoad event is fired as soon as the minimum version of
     * Flash Player is found.  It does not wait for SWFUpload to load and can
		 * be used to prepare the SWFUploadUI and hide alternate content.
     */
    onPreLoad: function()
    {
      this.setUploadProgress(new swfu_widget.SWFUploadProgress(this));
    },

    /**
     * swfuploadLoadFailed: Minimum flash version
     * not met.
     */
    onLoadFailed: function()
    {
      
    }
  },
  SWFUploadProgressBar: function(progress_container_node)
  {
    this._totalBytes    = 0;
    this._bytesLoaded   = 0;
    this._fullWidth     = 0;
    this._html = {};
    this._html.progressContainerNode = progress_container_node;
    this.renderHTML();
  },
  SWFUploadProgress : function(swfupload_instance)
  {
    this._files = {};
    this._swfupload = swfupload_instance;

    this._postParams = typeof this._swfupload.settings.post_params === 'object' ? this._swfupload.settings.post_params : {};
    
    var el = this._swfupload.movieElement;
    this._form = {};
    while(el = el.parentNode)
    {
      if(el.tagName.toLowerCase() === 'form')
      {
        this._form = el;break;
      }
    }
    this._sendSerializedValues = this._swfupload.customSettings.send_serialized_values;

    this._widget = document.getElementById(this._swfupload.customSettings.widget_id);
    this._collapseQueueOnInit = this._swfupload.customSettings.collapse_queue_on_init;
    this._preventFormSubmit   = this._swfupload.customSettings.prevent_form_submit;

    var self = this;
    if(this._preventFormSubmit && typeof this._form.submit !== 'undefined')
    {
      swfu_widget.addEventListener(this._form, 'submit', function(ev)
      {
        swfu_widget.Event.preventDefault(ev);
        if(self._sendSerializedValues)
          self.startUpload();
      });
    }

    this._message = '&nbsp;';
    this._bytesLoaded = 0;
    this._totalBytes  = 0;
    this._isError     = false;
    this._files_queued = false;
    this._isCancelled = false;
    this._isInProgress = false;
    this._view = new swfu_widget.SWFUploadProgressView(this);
    this._view.renderHTML();
    this._view.addObservers();
    this._progressBar = new swfu_widget.SWFUploadProgressBar(this.getView().getProgressContainer());
    this.addObservers();
    this.reset();
  },
  SWFUploadProgressView: function(swfupload_progress_instance)
  {
    this._html  = {};
    this._controller = swfupload_progress_instance;
  },

  /**
   * @param file:object
   * @param swfupload_progress_instance:SWFUploadProgress
   */
  SWFUploadProgressFile : function (file, swfupload_progress_instance)
  {
    this._isError           = false;
    this._isCancelled       = false;
    this._isAborted         = false;
    this._isOk              = false;
    this._isInProgress      = false;
    this._message           = null;
    this._observers         = [];
    this._file              = file;
    this._swfuploadProgress = swfupload_progress_instance;
    this._view              = new swfu_widget.SWFUploadProgressFileView(this);
    this._view.renderHTML();

    this._progressBar       = new swfu_widget.SWFUploadProgressBar(this.getView().getProgressContainer());

    this.observeControls();
    this.addObservers();
  },

  /**
   * @param swfupload_progress_file_instance:SWFUploadProgressFile
   */
  SWFUploadProgressFileView : function(swfupload_progress_file_instance)
  {
    this._html = {};
    this._controller = swfupload_progress_file_instance;
  }
};

swfu_widget.mixin(swfu_widget.handlers, swfu_widget.observable);

swfu_widget.FormSerializer.prototype.serialize = function()
{
  var form = this.form;
  var els = {};
  loop:for(var i=0; len=form.elements.length, i<len; i++)
  {
    var el = form.elements[i];
    if(
      !el.name ||
      (typeof el.disabled !== 'undefined' && el.disabled) ||
      (typeof el.checked !== 'undefined' && ['radio', 'checkbox'].indexOf(el.type.toLowerCase()) !== -1 && el.checked === false) ||
      (typeof el.selectedIndex !== 'undefined' && el.selectedIndex === -1) ||
      ['input', 'textarea', 'select'].indexOf(el.tagName.toLowerCase()) === -1
    ) continue loop;
    
    switch(el.tagName.toLowerCase())
    {
      case 'input':
        switch(el.type)
        {
          case 'hidden':
          case 'text':
          case 'password':
            if(typeof els[el.name] === 'undefined')
              els[el.name] = [];
            els[el.name].push(el.value);
            break;
          case 'radio':
          case 'checkbox':
            if(typeof els[el.name] === 'undefined')
              els[el.name] = [];
            if(el.checked)
              els[el.name].push(el.value);
            break;
        }
        break;
      case 'textarea':
        if(typeof els[el.name] === 'undefined')
          els[el.name] = [];
        els[el.name].push(el.value);
        break;
      case 'select':
        if(el.selectedIndex !== -1)
        {
          if(typeof els[el.name] === 'undefined')
            els[el.name] = [];
          for(var j=0; len2=el.options.length, j<len2; j++)
          {
            if(el.options[j].selected)
            {
              els[el.name].push(el.options[j].value);
            }
          }
        }
        break;
    }
  }
  var cleaned_els = {};
  for(var i in els)
  {
    var el = els[i];
    
    if(i.lastIndexOf('[]') === i.length - 2)
    {
      var base_name = i.substring(0, i.lastIndexOf('[]'));
      
      // reindex
      for(var j=0; len=el.length, j<len; j++)
      {
        cleaned_els[base_name + "["+j+"]"] = el[j];
      }
    }
    else
    {
      cleaned_els[i] = el.pop().toString();
    }
  }
  return cleaned_els;
}


// SWFUploadProgress

swfu_widget.SWFUploadProgress.prototype.addObservers = function()
{
  swfu_widget.addObserver(this.getView(), 'queue_cancelled', function(ev)
  {
    if(!this.getIsInProgress())return;
    this.getSWFUpload().cancelQueue();
    this.setMessage('Queue cancelled');
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'file_dialog_start', function()
  {
    this.reset();
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'file_queued', function(event)
  {
    var file = this.addFile(event.file);
    file.setMessage('File queued');
    this._files_queued++;
    this._totalBytes += event.file.size;
    this.setMessage(this._files_queued+ ' file(s) queued');
    if(this.getIsError())
      this.setMessage(this.getMessage() +  '<br/>Error: One or more files could not be queued, look at the file list for further details.');
    this.getView().updateUI();
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'file_queue_error', function(event)
  {
    this.setIsError(true);
    var file = this.addFile(event.file);
    this.setMessage(this.getMessage() +  '<br/>Error: One or more files could not be queued, look at the file list for further details.');
    file.setIsError(true);
    switch(event.error_code)
    {
      case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
        file.setMessage('File exceeds size limit.');
        break;
      case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
        file.setMessage('File is of an invalid type.')
        break;
      case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
        file.setMessage('Number of Files exeed file queue limit.')
        break;
      case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
        file.setMessage('Cannot upload 0 byte file.')
        break;
    }
    this.getView().updateUI();
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'file_dialog_complete', function(event)
  {
    this.getView().updateUI();
  }, this);

  swfu_widget.addObserver(this, 'begin_upload', function(event)
  {
    // serialize form values
    if(this._sendSerializedValues)
    {
      var f = new swfu_widget.FormSerializer(this._form);
      this.getSWFUpload().setUploadURL(this._form.action);
      this.getSWFUpload().setPostParams(swfu_widget.mixin(this._postParams, f.serialize()));
      this.getSWFUpload().addPostParam('swfupload_filesource', 'swf_upload');
    }
    this.setMessage('Upload in progress...');
    this.getView().disableBrowseButton();
    this.setIsInProgress(true);
    this.setIsError(false);
  }, this);

  swfu_widget.addObserver(swfu_widget.handlers, 'upload_start', function(event)
  {
    //this.setMessage('Upload in progress...');
    //this.getView().disableBrowseButton();
    //this.setIsInProgress(true);
    this._prev_bytes_complete=0;
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_progress', function(event)
  {
    this._bytesLoaded += event.bytes_complete - this._prev_bytes_complete;
    this._prev_bytes_complete = event.bytes_complete;
    this.getProgressBar().setBytesLoaded(this._bytesLoaded);
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_error', function(event)
  {
    if(event.error_code === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED || event.error_code === SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED)
    {
      this.setIsCancelled(true);
    }
    else
    {
      this.setIsError(true);
    }
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_complete', function(event)
  {
    this.getView().updateUI();
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'queue_complete', function(event)
  {
    this.getView().enableBrowseButton();
    this.setIsInProgress(false);
    if(this.getIsError())
      this.setMessage('Errors occured during upload.');
    else if(this.getIsCancelled())
      this.setMessage('Upload complete. A few files were cancelled during upload.');
    else
      this.setMessage('All files were successfully uploaded.');
    this.getView().disableCancelButton();
    this.getView().disableStartButton();
  }, this);
}

swfu_widget.SWFUploadProgress.prototype.reset  = function()
{
  for(var i in this._files)
  {
    this.getSWFUpload().cancelUpload(this._files[i].getFile().id, false);
    this._files[i].reset();
  }
  this._progressBar.reset();
  this._bytesLoaded   = 0;
  this._totalBytes    = 0;
  this._files_queued  = 0;
  this._files = {};
  this._isInProgress = false;
  this.setIsError(false);
  this.setIsCancelled(false);
  this.setMessage('Queue empty');
}

swfu_widget.SWFUploadProgress.prototype.getFilesQueued = function()
{
  return this._files_queued;
}

swfu_widget.SWFUploadProgress.prototype.getIsInProgress  = function()
{
  return this._isInProgress;
}

swfu_widget.SWFUploadProgress.prototype.setIsInProgress  = function(is_in_progress)
{
  this._isInProgress = is_in_progress;
}

swfu_widget.SWFUploadProgress.prototype.getView = function()
{
  return this._view;
}

swfu_widget.SWFUploadProgress.prototype.getProgressBar = function()
{
  return this._progressBar;
}

swfu_widget.SWFUploadProgress.prototype.getTotalBytes = function()
{
  return this._totalBytes;
}

swfu_widget.SWFUploadProgress.prototype.getBytesLoaded = function()
{
  return this._bytesLoaded;
}

/**
 * @return swfupload:SWFUpload
 */
swfu_widget.SWFUploadProgress.prototype.getSWFUpload = function()
{
  return this._swfupload;
}

swfu_widget.SWFUploadProgress.prototype.setIsError = function(is_error)
{
  this._isError = is_error;
  this.getView().updateUI();
}

swfu_widget.SWFUploadProgress.prototype.getIsError = function()
{
  return this._isError;
}

swfu_widget.SWFUploadProgress.prototype.setIsCancelled = function(is_cancelled)
{
  this._isCancelled = is_cancelled;
  this.getView().updateUI();
}

swfu_widget.SWFUploadProgress.prototype.getIsCancelled = function()
{
  return this._isCancelled;
}

swfu_widget.SWFUploadProgress.prototype.setMessage = function(message)
{
  this._message = message;
  this.getView().updateUI();
}

swfu_widget.SWFUploadProgress.prototype.startUpload = function()
{
  if(this.getIsInProgress() || ! this.getSWFUpload().getStats().files_queued) return;
  
  swfu_widget.fire(this, 'begin_upload');

  if(swfu_widget.OS == OS_FLAGS.LINUX || swfu_widget.OS == OS_FLAGS.UNIX)
  {
    if(!confirm('SWFUpload is known to have issues on Linux flash players due to a flash player bug. Do you really want to start the upload? Your browser may freeze during upload progress.'))
      return false;
  }
  this.getProgressBar().show();
  this.getProgressBar().setTotalBytes(this.getTotalBytes());
  this.getSWFUpload().startUpload();
}

swfu_widget.SWFUploadProgress.prototype.getMessage = function()
{
  return this._message;
}

/**
 * @param file:object
 */
swfu_widget.SWFUploadProgress.prototype.addFile = function(file)
{
  var file_instance = new swfu_widget.SWFUploadProgressFile(file, this);
  swfu_widget.addObserver(file_instance, 'upload_cancelled', function(event)
  {
    // For progress bar, update the total file size value.
    this._totalBytes = this._totalBytes - event.file.getFile().size;
    this._files_queued --;
    this.getView().updateUI();
    if(!this.getIsInProgress())
    {
      if(this._files_queued > 0)
      {
        this.setMessage(this._files_queued+ ' file(s) queued');
      }
      else
      {
        this.setMessage('Queue empty');
      }
      if(this.getIsError())
        this.setMessage(this.getMessage() +  '<br/>Error: One or more files could not be queued, look at the file list for further details.');
    }
  }, this);
  this.getView().appendFile(file_instance.getView());
  this._files[file.id] = file_instance;
  return this._files[file.id];
}

// SWFUploadProgressView

/**
 * @return controller:SWFUploadProgress
 */
swfu_widget.SWFUploadProgressView.prototype.getController = function()
{
  return this._controller;
}

swfu_widget.SWFUploadProgressView.prototype.getProgressContainer = function()
{
  return this._html.progressContainerNode;
}

swfu_widget.SWFUploadProgressView.prototype.renderHTML = function()
{
  var flash_object  = this.getController().getSWFUpload().movieElement;
  
  this._html.swfuploadWrapper       = document.createElement('div');

  this._html.containerNode          = document.createElement('div');
  this._html.messageContainer       = document.createElement('div');

  this._html.fileContainerNode      = document.createElement('ul');
  this._html.progressContainerNode  = document.createElement('div');
  this._html.controlsContainerNode  = document.createElement('div');

  this._html.toggleQueueLink        = document.createElement('a');
  this._html.toggleQueueLinkInner   = document.createElement('span');

  this._html.cancelUploadLink       = document.createElement('a');
  this._html.cancelUploadLinkInner  = document.createElement('span');
  
  this._html.browseFilesLink        = document.createElement('a');
  this._html.browseFilesLinkInner   = document.createElement('span');

  this._html.startUploadLink        = document.createElement('a');
  this._html.startUploadLinkInner   = document.createElement('span');

  this._html.headline               = document.createElement('h3');

  // Attributes
  this._html.swfuploadWrapper.className         = 'swfupload-wrapper';

  this._html.containerNode.className            = 'swfupload-queue';
  this._html.progressContainerNode.className    = 'swfupload-queue-progresscontainer';
  this._html.controlsContainerNode.className    = 'swfupload-queue-controlscontainer';
  this._html.messageContainer.className         = 'swfupload-queue-messagecontainer';
  
  this._html.cancelUploadLink.className         = 'swfupload-queue-canceluploadlink';
  this._html.startUploadLink.className          = 'swfupload-queue-startuploadlink';
  this._html.browseFilesLink.className          = 'swfupload-queue-browsefileslink';

  this._html.toggleQueueLink.className          = 'swfupload-queue-togglequeuelink swfupload-queue-togglequeuelink-expanded';

  // Content
  this._html.cancelUploadLinkInner.innerHTML = 'Cancel upload';
  this._html.cancelUploadLink.appendChild(this._html.cancelUploadLinkInner);
  this._html.cancelUploadLink.href="javascript:void(0)";

  this._html.startUploadLinkInner.innerHTML = 'Start upload';
  this._html.startUploadLink.appendChild(this._html.startUploadLinkInner);
  this._html.startUploadLink.href="javascript:void(0)";

  this._html.browseFilesLinkInner.innerHTML = 'Browse files';
  this._html.browseFilesLink.appendChild(this._html.browseFilesLinkInner);
  this._html.browseFilesLink.href="javascript:void(0)";

  this._html.toggleQueueLinkInner.innerHTML = 'Toggle queue';
  this._html.toggleQueueLink.appendChild(this._html.toggleQueueLinkInner);
  this._html.toggleQueueLink.href="javascript:void(0)";

  this._html.controlsContainerNode.appendChild(this._html.browseFilesLink);
  this._html.controlsContainerNode.appendChild(this._html.startUploadLink);
  this._html.controlsContainerNode.appendChild(this._html.cancelUploadLink);

  this._html.messageContainer.innerHTML = '&nbsp;';

  this._html.headline.innerHTML = 'Overall progress';
  
  this._html.containerNode.appendChild(this._html.messageContainer);
  this._html.containerNode.appendChild(this._html.headline);
  this._html.containerNode.appendChild(this._html.progressContainerNode);
  this._html.containerNode.appendChild(this._html.toggleQueueLink);
  this._html.containerNode.appendChild(this._html.fileContainerNode);

  flash_object.parentNode.insertBefore(this._html.swfuploadWrapper, flash_object);

  this._html.swfuploadWrapper.appendChild(this._html.controlsContainerNode);
  this._html.swfuploadWrapper.appendChild(this._html.containerNode);
  this._html.controlsContainerNode.appendChild(flash_object.parentNode.removeChild(flash_object));
  
  this.getController()._widget.parentNode.removeChild(this.getController()._widget);

  this.disableCancelButton();
  this.disableStartButton();

  var self = this;
  window.setTimeout(function()
  {
    flash_object.style.height = self._html.browseFilesLink.offsetHeight + "px";
    flash_object.style.width  = self._html.browseFilesLink.offsetWidth + "px";
    flash_object.style.left    = self._html.browseFilesLink.offsetLeft + "px";
    flash_object.style.top      = self._html.browseFilesLink.offsetTop + "px";
    flash_object.setAttribute('height', self._html.browseFilesLink.offsetHeight);
    flash_object.setAttribute('width', self._html.browseFilesLink.offsetWidth);
    try
    {
      self.getController().getSWFUpload().setButtonDimensions(self._html.browseFilesLink.offsetWidth, self._html.browseFilesLink.offsetHeight);
    } catch (e){;}
  },0);

  // Hide queue on init if set
  if(this.getController()._collapseQueueOnInit)
  {
    this.toggleQueueDisplay();
  }
}

swfu_widget.SWFUploadProgressView.prototype.updateUI = function()
{
  if(this.getController().getIsError())
    swfu_widget.addClassName(this._html.containerNode, 'swfupload-queue-error');
  else
    swfu_widget.removeClassName(this._html.containerNode,'swfupload-queue-error');

  this._html.messageContainer.innerHTML = this.getController().getMessage() ? this.getController().getMessage() : '&nbsp;';

  this._html.toggleQueueLink.style.display = 'block';
  if(this.getController().getFilesQueued() < 1)
  {
    this._html.toggleQueueLink.style.display = 'none';
    this.disableCancelButton();
    this.disableStartButton();
    this.enableBrowseButton();
  }
  else
  {
    if(this.getController().getIsInProgress())
    {
      this.enableCancelButton();
      this.disableStartButton();
    }
    else
    {
      this.enableStartButton();
      this.disableCancelButton();
    }
  }
}

swfu_widget.SWFUploadProgressView.prototype.toggleQueueDisplay = function()
{
  if(typeof YAHOO === 'undefined')
  {
    if(this._html.fileContainerNode.style.display=='none')
    {
      this._html.fileContainerNode.style.display='block';
      swfu_widget.addClassName(this._html.toggleQueueLink, 'swfupload-queue-togglequeuelink-expanded');
    }
    else
    {
      this._html.fileContainerNode.style.display='none';
      swfu_widget.removeClassName(this._html.toggleQueueLink, 'swfupload-queue-togglequeuelink-expanded');
    }
  }
  else
  {
    var self = this;
    if(this._html.fileContainerNode.style.display=='none')
    {
      this._html.fileContainerNode.style.display = "block";
      this._html.fileContainerNode.style.height = "auto";
      var targetH = this._html.fileContainerNode.offsetHeight;
      this._html.fileContainerNode.style.height = "0px";
        
      var myAnim = new YAHOO.util.Anim(this._html.fileContainerNode);
      myAnim.attributes.height = { from: 0, to: targetH };
      myAnim.duration = .4;
      myAnim.method = YAHOO.util.Easing.easeIn;
      myAnim.animate();
      myAnim.onComplete.subscribe(function()
      {
        this.getEl().style.height ="auto";
        swfu_widget.fire(self.getController(), 'queue_expanded');
        swfu_widget.addClassName(self._html.toggleQueueLink, 'swfupload-queue-togglequeuelink-expanded');
      });
    }
    else
    {
      var myAnim = new YAHOO.util.Anim(this._html.fileContainerNode);
      myAnim.attributes.height = { to: 0 };
      myAnim.duration = .4;
      myAnim.method = YAHOO.util.Easing.easeIn;
      myAnim.animate();
      myAnim.onComplete.subscribe(function(){
        this.getEl().style.display = "none";
        swfu_widget.removeClassName(self._html.toggleQueueLink, 'swfupload-queue-togglequeuelink-expanded');
      });
    }
  }
}

swfu_widget.SWFUploadProgressView.prototype.addObservers = function()
{
  var upload_progress = this.getController();
  var self = this;

  swfu_widget.addObserver(upload_progress, 'begin_upload', function(ev)
  {
    swfu_widget.removeClassName(this._html.containerNode, 'swfupload-queue-error');
    this.disableStartButton();
    this.enableCancelButton();
  }, this);

  swfu_widget.addEventListener(this._html.toggleQueueLink, 'click', function(ev)
  {
    self.toggleQueueDisplay();
    swfu_widget.Event.preventDefault(ev);
  });

  swfu_widget.addEventListener(this.getController().getSWFUpload().movieElement, 'mouseover', function(ev)
  {
    swfu_widget.addClassName(self._html.browseFilesLink, 'mouseover');
  });

  swfu_widget.addEventListener(this.getController().getSWFUpload().movieElement, 'mouseout', function(ev)
  {
    swfu_widget.removeClassName(self._html.browseFilesLink, 'mouseover');
  });
  
  swfu_widget.addEventListener(this._html.startUploadLink, 'click', function(ev)
  {
    self.getController().startUpload();
    swfu_widget.Event.preventDefault(ev);
  });

  swfu_widget.addEventListener(this._html.cancelUploadLink, 'click', function(ev)
  {
    swfu_widget.Event.preventDefault(ev);
    swfu_widget.fire(self, 'queue_cancelled');
    self.disableStartButton();
    self.disableCancelButton();
    self.enableBrowseButton();
  });
}

swfu_widget.SWFUploadProgressView.prototype.showControls = function()
{
  this._html.controlsContainerNode.style.display = 'block';
}

swfu_widget.SWFUploadProgressView.prototype.hideControls = function()
{
  this._html.controlsContainerNode.style.display = 'none';
}

swfu_widget.SWFUploadProgressView.prototype.disableCancelButton = function()
{
  swfu_widget.addClassName(this._html.cancelUploadLink, 'disabled');
}

swfu_widget.SWFUploadProgressView.prototype.enableCancelButton = function()
{
  swfu_widget.removeClassName(this._html.cancelUploadLink, 'disabled');
}

swfu_widget.SWFUploadProgressView.prototype.disableStartButton = function()
{
  swfu_widget.addClassName(this._html.startUploadLink, 'disabled');
}

swfu_widget.SWFUploadProgressView.prototype.enableStartButton = function()
{
  swfu_widget.removeClassName(this._html.startUploadLink, 'disabled');
}

swfu_widget.SWFUploadProgressView.prototype.disableBrowseButton = function()
{
  swfu_widget.addClassName(this._html.browseFilesLink, 'disabled');
}

swfu_widget.SWFUploadProgressView.prototype.enableBrowseButton = function()
{
  swfu_widget.removeClassName(this._html.browseFilesLink, 'disabled');
}

/**
 * @param file:SWFUploadProgressFileView
 */
swfu_widget.SWFUploadProgressView.prototype.appendFile = function(file)
{
  this._html.fileContainerNode.appendChild(file.getContainerNode());
}

// SWFUploadProgressFile

/**
 * @return view:SWFUploadProgressFileView
 */
swfu_widget.SWFUploadProgressFile.prototype.getView = function()
{
  return this._view;
}

swfu_widget.SWFUploadProgressFile.prototype.getProgressBar = function()
{
  return this._progressBar;
}

/**
 * @return file:object
 */
swfu_widget.SWFUploadProgressFile.prototype.getFile = function()
{
  return this._file;
}

/**
 * @return swfuploadProgress:SWFUploadProgress
 */
swfu_widget.SWFUploadProgressFile.prototype.getSWFUploadProgress = function()
{
  return this._swfuploadProgress;
}

swfu_widget.SWFUploadProgressFile.prototype.reset = function()
{
  this.getView().destroy();
}

/**
 * @param is_error:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.setIsError = function(is_error)
{
  this._isError = is_error;
  this.getView().updateUI();
}

/**
 * @param message:string
 */
swfu_widget.SWFUploadProgressFile.prototype.setMessage = function(message)
{
  this._message = message;
  this.getView().updateUI();
}

swfu_widget.SWFUploadProgressFile.prototype.cancelUpload = function()
{
  this.getSWFUploadProgress().getSWFUpload().cancelUpload(this._file.id, true);
  swfu_widget.fire(this, 'upload_cancelled', {file: this});
  this.setIsCancelled(true);
  this.setIsInProgress(false);
}

swfu_widget.SWFUploadProgressFile.prototype.stopUpload = function()
{
  this.getSWFUploadProgress().getSWFUpload().stopUpload();
  this.setIsAborted(true);
  swfu_widget.fire(this, 'upload_stopped', {file: this});
}

swfu_widget.SWFUploadProgressFile.prototype.observeControls = function()
{
  var self=this;
  swfu_widget.addEventListener(this.getView().getCancelButton(), 'click', function(ev)
  {
    if(self.getIsInProgress())
    {
      self.stopUpload();
    }
    self.cancelUpload();
    swfu_widget.Event.preventDefault(ev);
  }, this);
}

swfu_widget.SWFUploadProgressFile.prototype.addObservers = function()
{
  // Workaround, update progress bar, if the queue was previously hidden.
  swfu_widget.addObserver(this.getSWFUploadProgress(), 'queue_expanded', function(event)
  {
    this.getProgressBar().updateUI();
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_start', function(event)
  {
    if(event.file.id == this._file.id)
    {
      this.getProgressBar().show();
      this.getProgressBar().setTotalBytes(this._file.size);
      this.setIsInProgress(true);
      this.setMessage('Start upload');
    }
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_progress', function(event)
  {
    if(event.file.id == this._file.id)
    {
      this.setMessage('Uploading...');
      this.getProgressBar().setBytesLoaded(event.bytes_complete);
    }
  }, this);

  swfu_widget.addObserver(swfu_widget.handlers, 'upload_error', function(event)
  {
    if(event.file.id == this._file.id)
    {
      this.setIsError(true);
      switch (event.error_code)
      {
        case SWFUpload.UPLOAD_ERROR.HTTP_ERROR :
          // message is status code
          this.setMessage('An HTTP error occured: ' + event.message + "&nbsp;" + swfu_widget.HTTP_STATUS[event.message]);
          break;
        case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL :
          this.setMessage('Missing upload url, aborting.');
          break;
        case SWFUpload.UPLOAD_ERROR.IO_ERROR :
          this.setMessage('Upload IO error');
          break;
        case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
          this.setMessage('Security error');
          break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
          this.setMessage('File size limit exceeded.');
          break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
          this.setMessage('Upload failed.')
          break;
        case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
          this.setMessage('File not found error');
          break;
        case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
          this.setMessage('This file type is not allowed.')
          break;
        case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
          this.setMessage('Upload cancelled.')
          this.setIsCancelled(true);
          break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
          this.setMessage('Upload aborted');
          this.setIsAborted(true);
          break;
      }
      this.getView().updateUI();
    }
  }, this);
  
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_success', function(event)
  {
    if(event.file.id == this._file.id)
    {
      // Server data or default message?
      if(event.server_data)
      {
        var json;
        try
        {
          json=JSON.parse(event.server_data);
          
        }
        catch(e){json=false; }

        if(json===false)
        {
          this.setIsOk(true);
          this.setMessage(event.server_data);
        }
        else
        {
          if(json.isError === true)
          {
            this.getSWFUploadProgress().setIsError(true);
            this.setIsError(true);
          }
          else
          {
            this.setIsOk(true);
          }
          this.setMessage(json.message);
        }
      }
      else
      {
        this.setIsOk(true);
        this.setMessage('File uploaded');
      }
      this.getProgressBar().setBytesLoaded(this._file.size);
    }
  }, this);
  swfu_widget.addObserver(swfu_widget.handlers, 'upload_complete', function(event)
  {
    if(event.file.id == this._file.id)
    {
      this.setIsInProgress(false);
    }
  }, this);
}

/**
 * @return message:string
 */
swfu_widget.SWFUploadProgressFile.prototype.getMessage = function()
{
  return this._message;
}

/**
 * @return isError:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.getIsError = function()
{
  return this._isError;
}

/**
 * @param is_cancelled:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.setIsCancelled = function(is_cancelled)
{
  this._isCancelled = is_cancelled;
  this.getView().updateUI();
}

/**
 * @return isCancelled:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.getIsCancelled = function()
{
  return this._isCancelled;
}

/**
 * @param is_aborted:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.setIsAborted = function(is_aborted)
{
  this._isAborted = is_aborted;
  this.getView().updateUI();
}

/**
 * @return isAborted:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.getIsAborted = function()
{
  return this._isAborted;
}

/**
 * @param is_ok:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.setIsOk = function(is_ok)
{
  this._isOk = is_ok;
  this.getView().updateUI();
}

/**
 * @return isOk:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.getIsOk = function()
{
  return this._isOk;
}

/**
 * @param is_ok:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.setIsInProgress = function(is_in_progress)
{
  this._isInProgress = is_in_progress;
  this.getView().updateUI();
}

/**
 * @return isOk:boolean
 */
swfu_widget.SWFUploadProgressFile.prototype.getIsInProgress = function()
{
  return this._isInProgress;
}

swfu_widget.mixin(swfu_widget.SWFUploadProgressFile.prototype, swfu_widget.observable);

// SWFUploadProgressFileView

/**
 * @return controller:SWFUploadProgressFile
 */
swfu_widget.SWFUploadProgressFileView.prototype.getController = function()
{
  return this._controller;
}

/**
 * Updates the html code of the file, setting error classes and
 * so on.
 */
swfu_widget.SWFUploadProgressFileView.prototype.updateUI = function()
{
  switch(this.getController().getFile().filestatus)
  {
    
    case
      SWFUpload.FILE_STATUS.QUEUED:
      swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-queued');
      break;
    case SWFUpload.FILE_STATUS.IN_PROGRESS:
      swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-in-progress');
      break;
    // queue error
    case -6:
    case SWFUpload.FILE_STATUS.ERROR:
      swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-error');
      break;
    case SWFUpload.FILE_STATUS.COMPLETE:
      swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-ok');
      break;
    case SWFUpload.FILE_STATUS.CANCELLED:
      swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-cancelled');
      break;
  }
  
  if(this.getController().getIsError())
  {
    this._html.cancelUploadLink.style.display = 'none';
    swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-error');
  }

  if(this.getController().getIsOk())
  {
    this._html.cancelUploadLink.style.display = 'none';
    swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-ok');
  }

  if(this.getController().getIsAborted())
  {
    this._html.cancelUploadLink.style.display = 'none';
    swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-aborted');
  }

  if(this.getController().getIsCancelled())
  {
    this._html.cancelUploadLink.style.display = 'none';
    swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-cancelled');
    if(!this.getController().getSWFUploadProgress().getIsInProgress())
    {
      this.blendOut();
    }
  }
  
  if(this.getController().getIsInProgress())
  {
    swfu_widget.addClassName(this._html.containerNode,'swfupload-queued-file-in-progress');
  }
  else
  {
    swfu_widget.removeClassName(this._html.containerNode,'swfupload-queued-file-in-progress');
  }

  this._html.messageContainer.innerHTML = this.getController().getMessage() ? this.getController().getMessage() : '&nbsp;';
}

swfu_widget.SWFUploadProgressFileView.prototype.blendOut = function()
{
  if(typeof YAHOO === 'undefined')
  {
    this._html.containerNode.style.display = "none";
  }
  else
  {
    var myAnim = new YAHOO.util.Anim(this._html.containerNode);
    myAnim.attributes.height = { to: 0 };
    myAnim.duration = .4;
    myAnim.method = YAHOO.util.Easing.easeIn;
    myAnim.animate();
    myAnim.onComplete.subscribe(function(){this.getEl().style.display = "none";})
  }
};

swfu_widget.SWFUploadProgressFileView.prototype.renderHTML = function()
{
  var file = this.getController().getFile();

  this._html.containerNode              = document.createElement('li');

  this._html.progressBarContainer       = document.createElement('div');
  this._html.progressBar                = document.createElement('div');

  this._html.infoContainer              = document.createElement('div');
  this._html.infoFilename               = document.createElement('span');
  this._html.infoFilesize               = document.createElement('span');
  file.type && (this._html.infoFiletype = document.createElement('span'));
  
  this._html.controlsContainer          = document.createElement('div');
  this._html.cancelUploadLink           = document.createElement('a');
  
  this._html.messageContainer           = document.createElement('div');

  // Attributes
  this._html.containerNode.id = this.getController().getFile().id;

  this._html.containerNode.className                      = 'swfupload-queued-file';

  this._html.progressBarContainer.className               = 'swfupload-queued-file-progressbarcontainer';
  
  this._html.infoContainer.className                      = 'swfupload-queued-file-infocontainer';
  this._html.infoFilename.className                       = 'swfupload-queued-file-filename';
  this._html.infoFilesize.className                       = 'swfupload-queued-file-filesize';
  file.type && (this._html.infoFiletype.className         = 'swfupload-queued-file-filetype');

  this._html.cancelUploadLink.className                   = 'swfupload-queued-file-cancelbutton';
  this._html.controlsContainer.className                  = 'swfupload-queued-file-controlcontainer';

  this._html.messageContainer.className                   = 'swfupload-queued-file-messagecontainer';

  this._html.cancelUploadLink.href                        = 'javascript:void(0)';

  this._html.infoFilename.innerHTML = file.name;
  this._html.infoFilesize.innerHTML = swfu_widget.size2String(file.size);
  file.type && (this._html.infoFiletype.innerHTML = file.type);

  this._html.cancelUploadLink.innerHTML = '<span>Cancel</span>';

  this._html.messageContainer.innerHTML = '&nbsp;';

  this._html.infoContainer.appendChild(this._html.infoFilename);
  this._html.infoContainer.appendChild(this._html.infoFilesize);
  file.type && this._html.infoContainer.appendChild(this._html.infoFiletype);

  this._html.controlsContainer.appendChild(this._html.cancelUploadLink);

  this._html.containerNode.appendChild(this._html.messageContainer);
  this._html.containerNode.appendChild(this._html.controlsContainer);
  this._html.containerNode.appendChild(this._html.infoContainer);
  this._html.containerNode.appendChild(this._html.progressBarContainer);

  this.updateUI();
}

swfu_widget.SWFUploadProgressFileView.prototype.getCancelButton = function()
{
  return this._html.cancelUploadLink;
}

/**
 * @return containerNode:HtmlLIElement
 */
swfu_widget.SWFUploadProgressFileView.prototype.getContainerNode = function()
{
  return this._html.containerNode;
}

swfu_widget.SWFUploadProgressFileView.prototype.getProgressContainer = function()
{
  return this._html.progressBarContainer;
}

swfu_widget.SWFUploadProgressFileView.prototype.destroy = function()
{
  this._html.containerNode.parentNode.removeChild(this._html.containerNode);
}

// SWFUploadProgressBar
swfu_widget.SWFUploadProgressBar.prototype.setBytesLoaded = function(bytes_loaded)
{
  this._bytesLoaded = bytes_loaded;
  this.updateUI();
}

// SWFUploadProgressBar
swfu_widget.SWFUploadProgressBar.prototype.setTotalBytes = function(total_bytes)
{
  this._totalBytes = total_bytes;
  this.updateUI();
}

swfu_widget.SWFUploadProgressBar.prototype.show = function()
{
  this._html.progressContainerNode.style.display = "block";
}

swfu_widget.SWFUploadProgressBar.prototype.hide = function()
{
  this._html.progressContainerNode.style.display = "none";
}

swfu_widget.SWFUploadProgressBar.prototype.renderHTML = function()
{
  this._html.progressBarContainer = document.createElement('div');
  this._html.progressBar = document.createElement('div');
  this._html.progressText = document.createElement('div');
  this._html.progressBarWrapper = document.createElement('div');
  
  this._html.progressBarContainer.className = 'swfupload-progressbar-container';
  this._html.progressText.className         = 'swfupload-progressbar-text';
  this._html.progressBar.className          = 'swfupload-progressbar';
  this._html.progressBarWrapper.className   = 'swfupload-progressbar-wrapper';

  this._html.progressBar.innerHTML                = '&nbsp;';
  this._html.progressText.innerHTML               = '0&nbsp;%';
  
  this._html.progressContainerNode.style.display  = "block";
  this._html.progressBar.style.display            = "block";

  this._html.progressContainerNode.appendChild(this._html.progressBarWrapper);
  this._html.progressBarContainer.appendChild(this._html.progressBar);
  this._html.progressBarWrapper.appendChild(this._html.progressBarContainer);
  this._html.progressBarWrapper.appendChild(this._html.progressText);

  var self=this;
  // Dark hack
  window.setTimeout(function()
  {
    self._html.progressBar.style.width = 'auto';
    self._fullWidth = self._html.progressBar.offsetWidth;
    self.updateUI();
  },0);
}

swfu_widget.SWFUploadProgressBar.prototype.updateUI = function()
{
  // @todo: tu es nochmal aktualisieren, aber schön is dat nich.
  this._html.progressBar.style.width = 'auto';
  this._fullWidth = this._html.progressBar.offsetWidth;
  if(parseInt(this._totalBytes)===0)this._totalBytes=1;
  var fact  = this._bytesLoaded/this._totalBytes;
  var width = Math.round(fact * this._fullWidth);
  
  this._html.progressBar.style.width = width + "px";
  
  this._html.progressText.innerHTML = Math.round(fact * 100) + "&nbsp;%";
}

swfu_widget.SWFUploadProgressBar.prototype.reset = function()
{
  this.setTotalBytes(0);
  this.setBytesLoaded(0);
}

/******************* JSON PARSER ***********************************************/
/*
    http://www.JSON.org/json2.js
    2009-04-16

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html

    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the object holding the key.

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.

    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.
*/

/*jslint evil: true */

/*global JSON */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/

// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (!this.JSON) {
    JSON = {};
}
(function () {

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return this.getUTCFullYear()   + '-' +
                 f(this.getUTCMonth() + 1) + '-' +
                 f(this.getUTCDate())      + 'T' +
                 f(this.getUTCHours())     + ':' +
                 f(this.getUTCMinutes())   + ':' +
                 f(this.getUTCSeconds())   + 'Z';
        };

        String.prototype.toJSON =
        Number.prototype.toJSON =
        Boolean.prototype.toJSON = function (key) {
            return this.valueOf();
        };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ?
            '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === 'string' ? c :
                    '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' :
            '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0 ? '[]' :
                    gap ? '[\n' + gap +
                            partial.join(',\n' + gap) + '\n' +
                                mind + ']' :
                          '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    k = rep[i];
                    if (typeof k === 'string') {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0 ? '{}' :
                gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' +
                        mind + '}' : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                     typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/.
test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@').
replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function' ?
                    walk({'': j}, '') : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());