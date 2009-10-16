<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
 */

/**
 * sfFilebasePlugin Util is a static class that provides useful
 * methods for dealing with files.
 *
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
class sfFilebasePluginUtil
{
  /**
   * Holds an associative array
   * with mime-type as key and file-extension
   * as value.
   *
   * Taken from symfony-core-validator
   * sfValidatorFile.
   *
   * @author Fabien Potencier <fabien.potencier@symfony-project.com>
   * @var array $extensions
   * @staticvar array $extensions
   */
  private static $extensions = array(
    'application/andrew-inset' => 'ez',
    'application/appledouble' => 'base64',
    'application/applefile' => 'base64',
    'application/commonground' => 'dp',
    'application/cprplayer' => 'pqi',
    'application/dsptype' => 'tsp',
    'application/excel' => 'xls',
    'application/font-tdpfr' => 'pfr',
    'application/futuresplash' => 'spl',
    'application/hstu' => 'stk',
    'application/hyperstudio' => 'stk',
    'application/javascript' => 'js',
    'application/mac-binhex40' => 'hqx',
    'application/mac-compactpro' => 'cpt',
    'application/mbed' => 'mbd',
    'application/mirage' => 'mfp',
    'application/msword' => 'doc',
    'application/ocsp-request' => 'orq',
    'application/ocsp-response' => 'ors',
    'application/octet-stream' => 'bin',
    'application/oda' => 'oda',
    'application/ogg' => 'ogg',
    'application/pdf' => 'pdf',
    'application/x-pdf' => 'pdf',
    'application/pgp-encrypted' => '7bit',
    'application/pgp-keys' => '7bit',
    'application/pgp-signature' => 'sig',
    'application/pkcs10' => 'p10',
    'application/pkcs7-mime' => 'p7m',
    'application/pkcs7-signature' => 'p7s',
    'application/pkix-cert' => 'cer',
    'application/pkix-crl' => 'crl',
    'application/pkix-pkipath' => 'pkipath',
    'application/pkixcmp' => 'pki',
    'application/postscript' => 'ps',
    'application/presentations' => 'shw',
    'application/prs.cww' => 'cw',
    'application/prs.nprend' => 'rnd',
    'application/quest' => 'qrt',
    'application/rtf' => 'rtf',
    'application/sgml-open-catalog' => 'soc',
    'application/sieve' => 'siv',
    'application/smil' => 'smi',
    'application/toolbook' => 'tbk',
    'application/vnd.3gpp.pic-bw-large' => 'plb',
    'application/vnd.3gpp.pic-bw-small' => 'psb',
    'application/vnd.3gpp.pic-bw-var' => 'pvb',
    'application/vnd.3gpp.sms' => 'sms',
    'application/vnd.acucorp' => 'atc',
    'application/vnd.adobe.xfdf' => 'xfdf',
    'application/vnd.amiga.amu' => 'ami',
    'application/vnd.blueice.multipass' => 'mpm',
    'application/vnd.cinderella' => 'cdy',
    'application/vnd.cosmocaller' => 'cmc',
    'application/vnd.criticaltools.wbs+xml' => 'wbs',
    'application/vnd.curl' => 'curl',
    'application/vnd.data-vision.rdz' => 'rdz',
    'application/vnd.dreamfactory' => 'dfac',
    'application/vnd.fsc.weblauch' => 'fsc',
    'application/vnd.genomatix.tuxedo' => 'txd',
    'application/vnd.hbci' => 'hbci',
    'application/vnd.hhe.lesson-player' => 'les',
    'application/vnd.hp-hpgl' => 'plt',
    'application/vnd.ibm.electronic-media' => 'emm',
    'application/vnd.ibm.rights-management' => 'irm',
    'application/vnd.ibm.secure-container' => 'sc',
    'application/vnd.ipunplugged.rcprofile' => 'rcprofile',
    'application/vnd.irepository.package+xml' => 'irp',
    'application/vnd.jisp' => 'jisp',
    'application/vnd.kde.karbon' => 'karbon',
    'application/vnd.kde.kchart' => 'chrt',
    'application/vnd.kde.kformula' => 'kfo',
    'application/vnd.kde.kivio' => 'flw',
    'application/vnd.kde.kontour' => 'kon',
    'application/vnd.kde.kpresenter' => 'kpr',
    'application/vnd.kde.kspread' => 'ksp',
    'application/vnd.kde.kword' => 'kwd',
    'application/vnd.kenameapp' => 'htke',
    'application/vnd.kidspiration' => 'kia',
    'application/vnd.kinar' => 'kne',
    'application/vnd.llamagraphics.life-balance.desktop' => 'lbd',
    'application/vnd.llamagraphics.life-balance.exchange+xml' => 'lbe',
    'application/vnd.lotus-1-2-3' => 'wks',
    'application/vnd.mcd' => 'mcd',
    'application/vnd.mfmp' => 'mfm',
    'application/vnd.micrografx.flo' => 'flo',
    'application/vnd.micrografx.igx' => 'igx',
    'application/vnd.mif' => 'mif',
    'application/vnd.mophun.application' => 'mpn',
    'application/vnd.mophun.certificate' => 'mpc',
    'application/vnd.mozilla.xul+xml' => 'xul',
    'application/vnd.ms-artgalry' => 'cil',
    'application/vnd.ms-asf' => 'asf',
    'application/vnd.ms-excel' => 'xls',
    'application/vnd.ms-lrm' => 'lrm',
    'application/vnd.ms-powerpoint' => 'ppt',
    'application/vnd.ms-project' => 'mpp',
    'application/vnd.ms-tnef' => 'base64',
    'application/vnd.ms-works' => 'base64',
    'application/vnd.ms-wpl' => 'wpl',
    'application/vnd.mseq' => 'mseq',
    'application/vnd.nervana' => 'ent',
    'application/vnd.nokia.radio-preset' => 'rpst',
    'application/vnd.nokia.radio-presets' => 'rpss',
    'application/vnd.oasis.opendocument.text' => 'odt',
    'application/vnd.oasis.opendocument.text-template' => 'ott',
    'application/vnd.oasis.opendocument.text-web' => 'oth',
    'application/vnd.oasis.opendocument.text-master' => 'odm',
    'application/vnd.oasis.opendocument.graphics' => 'odg',
    'application/vnd.oasis.opendocument.graphics-template' => 'otg',
    'application/vnd.oasis.opendocument.presentation' => 'odp',
    'application/vnd.oasis.opendocument.presentation-template' => 'otp',
    'application/vnd.oasis.opendocument.spreadsheet' => 'ods',
    'application/vnd.oasis.opendocument.spreadsheet-template' => 'ots',
    'application/vnd.oasis.opendocument.chart' => 'odc',
    'application/vnd.oasis.opendocument.formula' => 'odf',
    'application/vnd.oasis.opendocument.database' => 'odb',
    'application/vnd.oasis.opendocument.image' => 'odi',
    'application/vnd.palm' => 'prc',
    'application/vnd.picsel' => 'efif',
    'application/vnd.pvi.ptid1' => 'pti',
    'application/vnd.quark.quarkxpress' => 'qxd',
    'application/vnd.sealed.doc' => 'sdoc',
    'application/vnd.sealed.eml' => 'seml',
    'application/vnd.sealed.mht' => 'smht',
    'application/vnd.sealed.ppt' => 'sppt',
    'application/vnd.sealed.xls' => 'sxls',
    'application/vnd.sealedmedia.softseal.html' => 'stml',
    'application/vnd.sealedmedia.softseal.pdf' => 'spdf',
    'application/vnd.seemail' => 'see',
    'application/vnd.smaf' => 'mmf',
    'application/vnd.sun.xml.calc' => 'sxc',
    'application/vnd.sun.xml.calc.template' => 'stc',
    'application/vnd.sun.xml.draw' => 'sxd',
    'application/vnd.sun.xml.draw.template' => 'std',
    'application/vnd.sun.xml.impress' => 'sxi',
    'application/vnd.sun.xml.impress.template' => 'sti',
    'application/vnd.sun.xml.math' => 'sxm',
    'application/vnd.sun.xml.writer' => 'sxw',
    'application/vnd.sun.xml.writer.global' => 'sxg',
    'application/vnd.sun.xml.writer.template' => 'stw',
    'application/vnd.sus-calendar' => 'sus',
    'application/vnd.vidsoft.vidconference' => 'vsc',
    'application/vnd.visio' => 'vsd',
    'application/vnd.visionary' => 'vis',
    'application/vnd.wap.sic' => 'sic',
    'application/vnd.wap.slc' => 'slc',
    'application/vnd.wap.wbxml' => 'wbxml',
    'application/vnd.wap.wmlc' => 'wmlc',
    'application/vnd.wap.wmlscriptc' => 'wmlsc',
    'application/vnd.webturbo' => 'wtb',
    'application/vnd.wordperfect' => 'wpd',
    'application/vnd.wqd' => 'wqd',
    'application/vnd.wv.csp+wbxml' => 'wv',
    'application/vnd.wv.csp+xml' => '8bit',
    'application/vnd.wv.ssp+xml' => '8bit',
    'application/vnd.yamaha.hv-dic' => 'hvd',
    'application/vnd.yamaha.hv-script' => 'hvs',
    'application/vnd.yamaha.hv-voice' => 'hvp',
    'application/vnd.yamaha.smaf-audio' => 'saf',
    'application/vnd.yamaha.smaf-phrase' => 'spf',
    'application/vocaltec-media-desc' => 'vmd',
    'application/vocaltec-media-file' => 'vmf',
    'application/vocaltec-talker' => 'vtk',
    'application/watcherinfo+xml' => 'wif',
    'application/wordperfect5.1' => 'wp5',
    'application/x-123' => 'wk',
    'application/x-7th_level_event' => '7ls',
    'application/x-authorware-bin' => 'aab',
    'application/x-authorware-map' => 'aam',
    'application/x-authorware-seg' => 'aas',
    'application/x-bcpio' => 'bcpio',
    'application/x-bleeper' => 'bleep',
    'application/x-bzip2' => 'bz2',
    'application/x-cdlink' => 'vcd',
    'application/x-chat' => 'chat',
    'application/x-chess-pgn' => 'pgn',
    'application/x-compress' => 'z',
    'application/x-cpio' => 'cpio',
    'application/x-cprplayer' => 'pqf',
    'application/x-csh' => 'csh',
    'application/x-cu-seeme' => 'csm',
    'application/x-cult3d-object' => 'co',
    'application/x-debian-package' => 'deb',
    'application/x-director' => 'dcr',
    'application/x-dvi' => 'dvi',
    'application/x-envoy' => 'evy',
    'application/x-futuresplash' => 'spl',
    'application/x-gtar' => 'gtar',
    'application/x-gzip' => 'gz',
    'application/x-hdf' => 'hdf',
    'application/x-hep' => 'hep',
    'application/x-html+ruby' => 'rhtml',
    'application/x-httpd-miva' => 'mv',
    'application/x-httpd-php' => 'phtml',
    'application/x-ica' => 'ica',
    'application/x-imagemap' => 'imagemap',
    'application/x-ipix' => 'ipx',
    'application/x-ipscript' => 'ips',
    'application/x-java-archive' => 'jar',
    'application/x-java-jnlp-file' => 'jnlp',
    'application/x-java-serialized-object' => 'ser',
    'application/x-java-vm' => 'class',
    'application/x-javascript' => 'js',
    'application/x-koan' => 'skp',
    'application/x-latex' => 'latex',
    'application/x-mac-compactpro' => 'cpt',
    'application/x-maker' => 'frm',
    'application/x-mathcad' => 'mcd',
    'application/x-midi' => 'mid',
    'application/x-mif' => 'mif',
    'application/x-msaccess' => 'mda',
    'application/x-msdos-program' => 'com',
    'application/x-msdownload' => 'base64',
    'application/x-msexcel' => 'xls',
    'application/x-msword' => 'doc',
    'application/x-netcdf' => 'nc',
    'application/x-ns-proxy-autoconfig' => 'pac',
    'application/x-pagemaker' => 'pm5',
    'application/x-perl' => 'pl',
    'application/x-pn-realmedia' => 'rp',
    'application/x-python' => 'py',
    'application/x-quicktimeplayer' => 'qtl',
    'application/x-rar-compressed' => 'rar',
    'application/x-ruby' => 'rb',
    'application/x-sh' => 'sh',
    'application/x-shar' => 'shar',
    'application/x-shockwave-flash' => 'swf',
    'application/x-sprite' => 'spr',
    'application/x-spss' => 'sav',
    'application/x-spt' => 'spt',
    'application/x-stuffit' => 'sit',
    'application/x-sv4cpio' => 'sv4cpio',
    'application/x-sv4crc' => 'sv4crc',
    'application/x-tar' => 'tar',
    'application/x-tcl' => 'tcl',
    'application/x-tex' => 'tex',
    'application/x-texinfo' => 'texinfo',
    'application/x-troff' => 't',
    'application/x-troff-man' => 'man',
    'application/x-troff-me' => 'me',
    'application/x-troff-ms' => 'ms',
    'application/x-twinvq' => 'vqf',
    'application/x-twinvq-plugin' => 'vqe',
    'application/x-ustar' => 'ustar',
    'application/x-vmsbackup' => 'bck',
    'application/x-wais-source' => 'src',
    'application/x-wingz' => 'wz',
    'application/x-word' => 'base64',
    'application/x-wordperfect6.1' => 'wp6',
    'application/x-x509-ca-cert' => 'crt',
    'application/x-zip' => 'zip',
    'application/x-zip-compressed' => 'zip',
    'application/xhtml+xml' => 'xhtml',
    'application/zip' => 'zip',
    'audio/3gpp' => '3gpp',
    'audio/amr' => 'amr',
    'audio/amr-wb' => 'awb',
    'audio/basic' => 'au',
    'audio/evrc' => 'evc',
    'audio/l16' => 'l16',
    'audio/midi' => 'mid',
    'audio/mpeg' => 'mp3',
    'audio/prs.sid' => 'sid',
    'audio/qcelp' => 'qcp',
    'audio/smv' => 'smv',
    'audio/vnd.audiokoz' => 'koz',
    'audio/vnd.digital-winds' => 'eol',
    'audio/vnd.everad.plj' => 'plj',
    'audio/vnd.lucent.voice' => 'lvp',
    'audio/vnd.nokia.mobile-xmf' => 'mxmf',
    'audio/vnd.nortel.vbk' => 'vbk',
    'audio/vnd.nuera.ecelp4800' => 'ecelp4800',
    'audio/vnd.nuera.ecelp7470' => 'ecelp7470',
    'audio/vnd.nuera.ecelp9600' => 'ecelp9600',
    'audio/vnd.sealedmedia.softseal.mpeg' => 'smp3',
    'audio/voxware' => 'vox',
    'audio/x-aiff' => 'aif',
    'audio/x-mid' => 'mid',
    'audio/x-midi' => 'mid',
    'audio/x-mpeg' => 'mp2',
    'audio/x-mpegurl' => 'mpu',
    'audio/x-pn-realaudio' => 'rm',
    'audio/x-pn-realaudio-plugin' => 'rpm',
    'audio/x-realaudio' => 'ra',
    'audio/x-wav' => 'wav',
    'chemical/x-csml' => 'csm',
    'chemical/x-embl-dl-nucleotide' => 'emb',
    'chemical/x-gaussian-cube' => 'cube',
    'chemical/x-gaussian-input' => 'gau',
    'chemical/x-jcamp-dx' => 'jdx',
    'chemical/x-mdl-molfile' => 'mol',
    'chemical/x-mdl-rxnfile' => 'rxn',
    'chemical/x-mdl-tgf' => 'tgf',
    'chemical/x-mopac-input' => 'mop',
    'chemical/x-pdb' => 'pdb',
    'chemical/x-rasmol' => 'scr',
    'chemical/x-xyz' => 'xyz',
    'drawing/dwf' => 'dwf',
    'drawing/x-dwf' => 'dwf',
    'i-world/i-vrml' => 'ivr',
    'image/bmp' => 'bmp',
    'image/cewavelet' => 'wif',
    'image/cis-cod' => 'cod',
    'image/fif' => 'fif',
    'image/gif' => 'gif',
    'image/ief' => 'ief',
    'image/jp2' => 'jp2',
    'image/jpeg' => 'jpg',
    'image/jpm' => 'jpm',
    'image/jpx' => 'jpf',
    'image/pict' => 'pic',
    'image/pjpeg' => 'jpg',
    'image/png' => 'png',
    'image/targa' => 'tga',
    'image/tiff' => 'tif',
    'image/vn-svf' => 'svf',
    'image/vnd.dgn' => 'dgn',
    'image/vnd.djvu' => 'djvu',
    'image/vnd.dwg' => 'dwg',
    'image/vnd.glocalgraphics.pgb' => 'pgb',
    'image/vnd.microsoft.icon' => 'ico',
    'image/vnd.ms-modi' => 'mdi',
    'image/vnd.sealed.png' => 'spng',
    'image/vnd.sealedmedia.softseal.gif' => 'sgif',
    'image/vnd.sealedmedia.softseal.jpg' => 'sjpg',
    'image/vnd.wap.wbmp' => 'wbmp',
    'image/x-bmp' => 'bmp',
    'image/x-cmu-raster' => 'ras',
    'image/x-freehand' => 'fh4',
    'image/x-png' => 'png',
    'image/x-portable-anymap' => 'pnm',
    'image/x-portable-bitmap' => 'pbm',
    'image/x-portable-graymap' => 'pgm',
    'image/x-portable-pixmap' => 'ppm',
    'image/x-rgb' => 'rgb',
    'image/x-xbitmap' => 'xbm',
    'image/x-xpixmap' => 'xpm',
    'image/x-xwindowdump' => 'xwd',
    'message/external-body' => '8bit',
    'message/news' => '8bit',
    'message/partial' => '8bit',
    'message/rfc822' => '8bit',
    'model/iges' => 'igs',
    'model/mesh' => 'msh',
    'model/vnd.parasolid.transmit.binary' => 'x_b',
    'model/vnd.parasolid.transmit.text' => 'x_t',
    'model/vrml' => 'wrl',
    'multipart/alternative' => '8bit',
    'multipart/appledouble' => '8bit',
    'multipart/digest' => '8bit',
    'multipart/mixed' => '8bit',
    'multipart/parallel' => '8bit',
    'text/comma-separated-values' => 'csv',
    'text/css' => 'css',
    'text/html' => 'html',
    'text/plain' => 'txt',
    'text/prs.fallenstein.rst' => 'rst',
    'text/richtext' => 'rtx',
    'text/rtf' => 'rtf',
    'text/sgml' => 'sgml',
    'text/tab-separated-values' => 'tsv',
    'text/vnd.net2phone.commcenter.command' => 'ccc',
    'text/vnd.sun.j2me.app-descriptor' => 'jad',
    'text/vnd.wap.si' => 'si',
    'text/vnd.wap.sl' => 'sl',
    'text/vnd.wap.wml' => 'wml',
    'text/vnd.wap.wmlscript' => 'wmls',
    'text/x-hdml' => 'hdml',
    'text/x-setext' => 'etx',
    'text/x-sgml' => 'sgml',
    'text/x-speech' => 'talk',
    'text/x-vcalendar' => 'vcs',
    'text/x-vcard' => 'vcf',
    'text/xml' => 'xml',
    'ulead/vrml' => 'uvr',
    'video/3gpp' => '3gp',
    'video/dl' => 'dl',
    'video/gl' => 'gl',
    'video/mj2' => 'mj2',
    'video/mpeg' => 'mpeg',
    'video/quicktime' => 'mov',
    'video/vdo' => 'vdo',
    'video/vivo' => 'viv',
    'video/vnd.fvt' => 'fvt',
    'video/vnd.mpegurl' => 'mxu',
    'video/vnd.nokia.interleaved-multimedia' => 'nim',
    'video/vnd.objectvideo' => 'mp4',
    'video/vnd.sealed.mpeg1' => 's11',
    'video/vnd.sealed.mpeg4' => 'smpg',
    'video/vnd.sealed.swf' => 'sswf',
    'video/vnd.sealedmedia.softseal.mov' => 'smov',
    'video/vnd.vivo' => 'vivo',
    'video/x-fli' => 'fli',
    'video/x-ms-asf' => 'asf',
    'video/x-ms-wmv' => 'wmv',
    'video/x-msvideo' => 'avi',
    'video/x-sgi-movie' => 'movie',
    'x-chemical/x-pdb' => 'pdb',
    'x-chemical/x-xyz' => 'xyz',
    'x-conference/x-cooltalk' => 'ice',
    'x-drawing/dwf' => 'dwf',
    'x-world/x-d96' => 'd',
    'x-world/x-svr' => 'svr',
    'x-world/x-vream' => 'vrw',
    'x-world/x-vrml' => 'wrl'
  );

  /**
   * This array describes recursive dependancies
   * of mime types
   *
   * @staticvar array
   * @var array
   */
  public static $mime_dependencies = array(
    'application/zip'=>array(
      'odt'
    )
  );

  /**
   * This array describes recursive dependancies
   * of extensions to check the mime type after
   *
   * @staticvar array
   * @var array
   */
  public static $extension_dependencies = array(
  );

  /**
   * Mime types of web images, as possibly 
   * provided by web-browsers.
   *
   * @staticvar array $WEB_IMAGES
   */
  public static $WEB_IMAGES = array
  (
    'image/x-png',
    'image/png',
    'image/jpeg',
    'image/pjpeg',
    'image/gif'
  );

  /**
   * Returns an associative array
   * with mime-type as key and file-extension
   * as value.
   *
   * @return array $extensions
   */
  public static function getMimeExtensions()
  {
    return self::$extensions;
  }

  /**
   *
   * @param string $extension
   * @param mixed $default
   * @return string mime-type
   */
  public static function getMimeByExtension($extension, $default = null)
  {
    $ret_val = array_search(strtolower($extension), self::$extensions);
    return $ret_val ? $ret_val : $default;
  }

  /**
   * Returns an corresponding file-extension
   * to given mime-type or $default if that
   * extension was not found.
   *
   * @param string $mime
   * @param mixed $default
   * @return string $extension
   */
  public static function getExtensionByMime($mime, $default=null)
  {
    $mime = strtolower($mime);
    return array_key_exists($mime, self::$extensions) ? self::$extensions[$mime] : $default;
  }

  /**
   * 
   * Replaces Backslashes and removes
   * trainling slash
   *
   * @param string $path
   * @return string $path unified
   */
  public static function unifySlashes($path)
  {
    return self::realpath($path);
  }

  /**
   * Replaces native php function realpath(), without
   * checking if the path physically exists on file system.
   * Returns false if the path could not be converted.
   * @param string $path
   * @return string $cleaned up path
   */
  public static function realpath($path)
  {
    $pathinfo = self::pathInfo($path);

    $path = preg_replace('#[\\\/]+#','/', $pathinfo['stripped_path']);
    
    $parts = explode('/', $path);
    $new_path = array();

    foreach($parts AS $i=>$part)
    {
      if(empty($part) || $part == '.')
      {
        continue;
      }
      if($part == '..')
      {
        if(count($new_path)>0)
        {
          array_pop($new_path);
          continue;
        }
        return false;
      }
      $new_path[] = $part;
    }
    $path = implode('/', $new_path);
    if($pathinfo['is_absolute'])
    {
      if($pathinfo['drive_letter'])
      {
        return $pathinfo['drive_letter'] . ':/' . $path;
      }
      return '/' . $path;
    }
    else return $path;
  }

  /**
   * Returns string representation of byte size.
   *
   * @param integer $size in bytes
   * @return string
   */
  public static function getStringFilesize($size)
  {
    $size = (double) $size;
    if($size < 1000)
      return $size . '&nbsp;Byte';
    else if($size < 1000000)
      return round($size / 1000) . '&nbsp;kB';
    else if($size < 1000000000)
      return round($size / (1000000), 2) . '&nbsp;MB';
    else
      return round($size / (1000000000), 2) . '&nbsp;GB';
  }

  /**
   * Parses .ini-Property maxUploadFileSize and returns it as
   * a byte-value
   *
   * @return int $size
   * @static
   * @throws sfFilebasePluginException
   */
  public static function getMaxUploadFileSize($default_size = null)
  {
    $raw_size = ini_get('upload_max_filesize') ? ini_get('upload_max_filesize') : ini_get('post_max_size');
    
    preg_match('#^([0-9]+?)([gmk])$#i', $raw_size, $tokens);
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

    if($default_size === null)
      throw new sfFilebasePluginException ('Parse error in ini-setting: ' . $raw_size);
    return $default_size;
  }

  /**
   * Retrieves the type of a file as a human readable
   * string by its extension.
   *
   * @param string $ext
   * @return string
   */
  public static function getStringTypeByExtension($ext)
  {
    $types = array(
      'gif'   => 'gif image',
      'jpg'   => 'jpeg image',
      'jpeg'  => 'jpeg image',
      'png'   => 'png image',
      'pdf'   => 'pdf document',
      'tiff'  => 'tiff image',
      'tif'   => 'tiff image',
      'mp3'   => 'mp3 audio file',
      'wav'   => 'wav audio file',
      'mp4'   => 'mp4 audio file',
      'mpeg'   =>'mpeg video file',
      'mpg'   => 'mpeg video file',
      'avi'   => 'avi video file',
      'ra'   => 'real audio file',
      'rv'   => 'real video file',
      'wmi'   => 'windows media file',
      'wma'   => 'windows media file',
      'doc'   => 'word document',
      'xls'   => 'excel data sheet',
      'ppt'   => 'powerpoint presentation'
    );
    if(isset($types[$ext]))
    {
      return $types[$ext];
    }
    return $ext . ' file';
  }

  /**
   * Checks if a pathname is absolute.
   *
   * @param string $pathname
   * @return boolean True if $pathname is absolute.
   */
  public static function isAbsolutePathname($pathname)
  {
    $pathinfo = self::pathInfo($pathname);
    return $pathinfo['is_absolute'];
  }

  /**
   * Returns some additional information about a raw pathname
   * as an array(
   *   is_absolute: true if the pathname is absolute
   *   drive_letter: the drive letter on windows
   *   fs: string (unix or win)
   *   pathname: the full pathname as given
   *   stripped_path: the pathname without the trailing slash and/or drive letter
   * )
   * @param string $pathname
   * @return array $info
   */
  public static function pathInfo($pathname)
  {
    preg_match('#^((/)|(([a-z]):(\\\|/)))?(.*?)$#i',$pathname, $matches);
    return array(
      'is_absolute'         => !empty($matches[1]) || !empty($matches[2]),
      'drive_letter'        => empty($matches[4]) ? null : strtoupper($matches[4]),
      'fs'                  => empty($matches[3]) ? 'unix' : 'win',
      'pathname'            => $pathname,
      'stripped_path'       => $matches[6]
    );
  }

  /**
   * Parses a css color notation into
   * a hexadecimal value.
   *
   * rgb(0,0,0); cmyl(0,0,0,0), #123456 ...
   *
   * @todo implement ;)
   * @param string $color
   */
  public static function parseHTMLColor($color)
  {
    return $color;
  }

  /**
   * Returns true if sfFilebasePluginFile is a
   * web image file. Used to factory
   * a sfFilebasePluginImage instance by sfFilebasePlugin::
   * getFilebaseFile()
   *
   * @todo improved mime-type detection
   * @return boolean true if file is an image
   */
  public static function getIsImage(sfFilebasePluginFile $file)
  { 
    return (strpos(self::getMimeType($file), 'image') === 0);
  }

  /**
   * Returns true if the image file is a web image.
   * 
   * @param sfFilebasePluginFile  $file
   * @return boolean              $is_web_image: true if $file is a web image.
   */
  public static function getIsWebImage(sfFilebasePluginFile $file)
  {
    return in_array(self::getMimeType($file), self::$WEB_IMAGES);
  }

  /**
   * Returns true if $file is a supported image, what means that it can
   * be processed by either GD-lib or imagick extension
   *
   * @param sfFilebasePluginFile $file
   * @return boolean $is_supported_image: True if $file is supported
   */
  public static function getIsSupportedImage(sfFilebasePluginFile $file)
  {
    $supported_mimes = array();
    
    // Check availabilty of gd library
    if(function_exists('gd_info'))
    {
      $image_types = imagetypes();
      $image_types & IMG_GIF && $supported_mimes[] = 'image/gif';
      if($image_types & IMG_JPG)
      {
        $supported_mimes[] = 'image/jpeg';
        $supported_mimes[] = 'image/pjpeg';
      }
      if($image_types & IMG_PNG)
      {
        $supported_mimes[] = 'image/png';
        $supported_mimes[] = 'image/x-png';
      }
      if($image_types &  IMG_WBMP)
      {
        $supported_mimes[] = 'image/wbmp';
        $supported_mimes[] = 'image/bmp';
        $supported_mimes[] = 'image/x-bmp';
      }
      if($image_types & IMG_XPM)
      {
        $supported_mimes[] = 'image/x-bitmap';
      }
    }

    // Assume imagick supports all image formats that have ever been
    // invented.
    // @todo Prove that false and implement better supported format check
    if(class_exists('imagick', false))
    {
      return self::getIsImage($file);
    }
    return self::getIsImage($file) && in_array(self::getMimeType($file), $supported_mimes);
  }

  /**
   * Trys to find the mime type of a file
   * and returns it, otherwise a $default value will
   * be returned (application/octet-stream per default)
   *
   * $file must be an instance of sfFilebasePluginFile
   *
   * @param   sfFilebasePluginFile  $absolute_path
   * @param   string                $default
   * @return  string               $mime_type
   */
  public static function getMimeType(sfFilebasePluginFile $file, $default = 'application/octet-stream', $extension = null)
  {
    $mime_type = false;

    $ext = $extension === null ? $file->getExtension() : $extension;
    
    // Using file_exists() instead of sfFilebasePluginFile::fileExists()
    // to avoid recursion issue. sfFilebasePluginFile::fileExists()
    // calls sfFilebasePlugin::getFilebaseFile() calls
    // sfFilebaseUtil::getMimeType()...
    if(file_exists($file->getPathname()))
    {
      if(!$file->isReadable()) throw new sfFilebasePluginException(sprintf('File %s is read protected.', $file->getPathname()));

      // 1st step, check magic mimeinfo
      if(class_exists('finfo'))
      {
        $finfo = new finfo(FILEINFO_MIME);
        $mime_type = $finfo->file($file->getPathname());
      }
      elseif(function_exists('mime_content_type'))
      {
        $mime_type = mime_content_type($file->getPathname());
      }
    }

    if($mime_type)
    {
      if(!empty($ext))
      {
        $ext_mime_type = self::getMimeByExtension($ext, null);
        if($ext_mime_type != $mime_type && array_key_exists($mime_type, self::$mime_dependencies) && in_array($ext, self::$mime_dependencies[$mime_type]))
        {
          return $ext_mime_type;
        }
      }
      return $mime_type;
    }
    
    // 3. check extension
    return self::getMimeByExtension($ext, $default);
  }
}