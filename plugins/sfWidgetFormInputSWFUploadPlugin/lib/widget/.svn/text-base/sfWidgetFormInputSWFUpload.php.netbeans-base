<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfWidgetFormInputSWFUpload
 *
 * @author joshi
 */
class sfWidgetFormInputSWFUpload extends sfWidgetFormInputFile
{
  /**
   * Instance counter
   *
   * @var integer
   */
  protected static $INSTANCE_COUNT = 0;

  protected function iniSize2Int($ini_size)
  {
    preg_match('#^([0-9]+?)([gmk])$#i', $ini_size, $tokens);
    $unit=null; $size_val=null;
    isset($tokens[1])&&$size_val  = $tokens[1];
    isset($tokens[2])&&$unit      = $tokens[2];
    if($size_val && $unit)
    {
      switch(strtolower($unit))
      {
        case 'k':
          return $size_val* 1000;
        case 'm':
          return $size_val* 1000000;
        case 'g':
          return $size_val* 1000000000;
        default:
          return $size_val;
      }
    }
  }

  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('custom_javascripts', array());

    $this->addOption('prevent_form_submit', true);

    $this->addOption('collapse_queue_on_init', true);

    $this->addOption('send_serialized_values', true);
    $this->addOption('require_yui', false);

    $this->addOption('swfupload_upload_url', $_SERVER['REQUEST_URI']);
    $this->addOption('swfupload_post_name', null);
    $this->addOption('swfupload_post_params', '');

    $this->addOption('swfupload_file_types', '*.jpg;*.jpeg;*.gif;*.png');
    $this->addOption('swfupload_file_types_description', 'Web images');

    $this->addOption('swfupload_file_size_limit', ini_get('upload_max_filesize'));
    $this->addOption('swfupload_file_upload_limit', 0);
    $this->addOption('swfupload_file_queue_limit', 0);
    
    $this->addOption('swfupload_flash_url',     '/sfWidgetFormInputSWFUploadPlugin/js/vendor/swfupload/Flash/swfupload.swf');

    $this->addOption('swfupload_css_path',      '/sfWidgetFormInputSWFUploadPlugin/css/swfupload.css');
    $this->addOption('swfupload_js_path',       '/sfWidgetFormInputSWFUploadPlugin/js/vendor/swfupload/swfupload.js');
    $this->addOption('swfupload_handler_path',  '/sfWidgetFormInputSWFUploadPlugin/js/swfupload-widget-handler.js');
    $this->addOption('swfupload_plugins_dir',   '/sfWidgetFormInputSWFUploadPlugin/js/vendor/swfupload/plugins');
    $this->addOption('swfupload_button_image_url', null);

    $this->addOption('swfupload_button_width', 1);
    $this->addOption('swfupload_button_height', 1);
    $this->addOption('swfupload_button_text', '');
    $this->addOption('swfupload_button_text_style', '');
    $this->addOption('swfupload_button_text_left_padding', 0);
    $this->addOption('swfupload_button_text_top_padding', 0);
    $this->addOption('swfupload_button_disabled', 'false');
    $this->addOption('swfupload_button_cursor', 'SWFUpload.CURSOR.ARROW');
    $this->addOption('swfupload_button_window_mode', 'SWFUpload.WINDOW_MODE.TRANSPARENT');
    $this->addOption('swfupload_button_action', 'SWFUpload.BUTTON_ACTION.SELECT_FILES');

    $this->addOption('swfupload_swfupload_loaded_handler', 'swfu_widget.handlers.onLoad');
    $this->addOption('swfupload_file_dialog_start_handler', 'swfu_widget.handlers.onFileDialogStart');
    $this->addOption('swfupload_file_queued_handler', 'swfu_widget.handlers.onFileQueued');
    $this->addOption('swfupload_file_queue_error_handler', 'swfu_widget.handlers.onFileQueueError');
    $this->addOption('swfupload_file_dialog_complete_handler', 'swfu_widget.handlers.onFileDialogComplete');
    $this->addOption('swfupload_upload_start_handler', 'swfu_widget.handlers.onUploadStart');
    $this->addOption('swfupload_upload_progress_handler', 'swfu_widget.handlers.onUploadProgress');
    $this->addOption('swfupload_upload_error_handler', 'swfu_widget.handlers.onUploadError');
    $this->addOption('swfupload_upload_success_handler', 'swfu_widget.handlers.onUploadSuccess');
    $this->addOption('swfupload_upload_complete_handler', 'swfu_widget.handlers.onUploadComplete');
    $this->addOption('swfupload_queue_complete_handler', 'swfu_widget.handlers.onQueueComplete');
    $this->addOption('swfupload_swfupload_pre_load_handler', 'swfu_widget.handlers.onPreLoad');
    $this->addOption('swfupload_swfupload_load_failed_handler', 'swfu_widget.handlers.onLoadFailed');
    $this->addOption('swfupload_minimum_flash_version', '9.0.28');
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array(
      $this->getOption('swfupload_css_path') => 'all'
    );
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $js = array(
      $this->getOption('swfupload_js_path'),
      $this->getOption('swfupload_handler_path'),
      $this->getOption('swfupload_plugins_dir') . '/swfupload.queue.js',
      $this->getOption('swfupload_plugins_dir') . '/swfupload.cookies.js',
      $this->getOption('swfupload_plugins_dir') . '/swfupload.speed.js',
      $this->getOption('swfupload_plugins_dir') . '/swfupload.swfobject.js'
    );
    if($this->getOption('require_yui'))
      $js[] = "http://yui.yahooapis.com/combo?2.7.0/build/yahoo-dom-event/yahoo-dom-event.js&2.7.0/build/animation/animation-min.js";
    return array_merge($js, $this->getOption('custom_javascripts'));
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    self::$INSTANCE_COUNT++;
    //*.jpg;*.gif
    $extensions = is_array($this->getOption('swf_upload_file_types')) ?
      implode(';', $this->getOption('swf_upload_file_types')):
      $this->getOption('swf_upload_file_types');

    $output = parent::render($name, $value, $attributes, $errors);

    $widget_id  = $this->getAttribute('id') ? $this->getAttribute('id') : $this->generateId($name);
    $button_id  = $widget_id . "_swfupload_target";

    $swfupload_button_image_url = $this->getOption('swfupload_button_image_url') === null ? '' : public_path($this->getOption('swfupload_button_image_url'));

    $max_size = $this->iniSize2Int($this->getOption('swfupload_file_size_limit'));

    $swfupload_post_name = $this->getOption('swfupload_post_name') === null ? $name : $this->getOption('swfupload_post_name');

    $send_serialized_values = $this->getOption('send_serialized_values') ? 'true' : 'false';

    $collapse_queue_on_init = $this->getOption('collapse_queue_on_init') ? 'true' : 'false';

    $prevent_form_submit = $this->getOption('prevent_form_submit') ? 'true' : 'false';

    $output .= <<<EOF
      <div class="swfupload-buttontarget" id="{$button_id}">
        <noscript>
          We're sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.
        </noscript>
      </div>
      <script type="text/javascript">
        //<![CDATA[
        SWFUpload.onload = function()
        {
          new SWFUpload
          ({
            upload_url : "{$this->getOption('swfupload_upload_url')}",
            flash_url : "{$this->getOption('swfupload_flash_url')}",
            button_placeholder_id : "{$button_id}",
            file_post_name : "{$swfupload_post_name}",
            post_params :
            {
              {$this->getOption('swfupload_post_params')}
            },
            custom_settings :
            {
              widget_id: "{$widget_id}",
              send_serialized_values: $send_serialized_values,
              collapse_queue_on_init: $collapse_queue_on_init,
              prevent_form_submit: $prevent_form_submit
            },
            use_query_string : false,
            requeue_on_error : false,
            assume_success_timeout : 0,
            file_types : "{$extensions}",
            file_types_description: "Web Image Files",
            file_size_limit : "{$max_size}",
            file_upload_limit : {$this->getOption('swfupload_file_upload_limit')},
            file_queue_limit : {$this->getOption('swfupload_file_queue_limit')},
            debug : false,
            prevent_swf_caching : true,
            preserve_relative_urls : false,
           
            button_image_url : "{$swfupload_button_image_url}",
            button_width : {$this->getOption('swfupload_button_width')},
            button_height : {$this->getOption('swfupload_button_height')},
            button_text : "{$this->getOption('swfupload_button_text')}",
            button_text_style : "{$this->getOption('swfupload_button_style')}",
            button_text_left_padding : {$this->getOption('swfupload_button_text_left_padding')},
            button_text_top_padding : {$this->getOption('swfupload_button_text_top_padding')},
            button_disabled : {$this->getOption('swfupload_button_disabled')},
            button_cursor : {$this->getOption('swfupload_button_cursor')},
            button_window_mode : {$this->getOption('swfupload_button_window_mode')},
            button_action : {$this->getOption('swfupload_button_action')},
            
            swfupload_loaded_handler : {$this->getOption('swfupload_swfupload_loaded_handler')},
            file_dialog_start_handler : {$this->getOption('swfupload_file_dialog_start_handler')},
            file_queued_handler : {$this->getOption('swfupload_file_queued_handler')},
            file_queue_error_handler : {$this->getOption('swfupload_file_queue_error_handler')},
            file_dialog_complete_handler : {$this->getOption('swfupload_file_dialog_complete_handler')},
            upload_start_handler : {$this->getOption('swfupload_upload_start_handler')},
            upload_progress_handler : {$this->getOption('swfupload_upload_progress_handler')},
            upload_error_handler : {$this->getOption('swfupload_upload_error_handler')},
            upload_success_handler : {$this->getOption('swfupload_upload_success_handler')},
            upload_complete_handler : {$this->getOption('swfupload_upload_complete_handler')},
            queue_complete_handler : {$this->getOption('swfupload_queue_complete_handler')},

            // swf object
            swfupload_pre_load_handler : {$this->getOption('swfupload_swfupload_pre_load_handler')},
            swfupload_load_failed_handler : {$this->getOption('swfupload_swfupload_load_failed_handler')},

            minimum_flash_version : "{$this->getOption('swfupload_minimum_flash_version')}"
          });
        }
        //]]>
      </script>
EOF;
    return $output;
  }
}