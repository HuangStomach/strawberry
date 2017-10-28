/*!
 * bootstrap-fileinput v4.4.6
 * http://plugins.krajee.com/file-input
 *
 * Font Awesome icon theme configuration for bootstrap-fileinput. Requires font awesome assets to be loaded.
 *
 * Author: Kartik Visweswaran
 * Copyright: 2014 - 2017, Kartik Visweswaran, Krajee.com
 *
 * Licensed under the BSD 3-Clause
 * https://github.com/kartik-v/bootstrap-fileinput/blob/master/LICENSE.md
 */
(function ($) {
  "use strict";

  $.fn.fileinputThemes.icomoon = {
      fileActionSettings: {
          removeIcon: '<i class="icon-bin"></i>',
          uploadIcon: '<i class="icon-upload"></i>',
          uploadRetryIcon: '<i class="icon-spinner"></i>',
          zoomIcon: '<i class="icon-zoom-in"></i>',
          dragIcon: '<i class="icon-menu"></i>',
          indicatorNew: '<i class="icon-plus text-warning"></i>',
          indicatorSuccess: '<i class="icon-check text-success"></i>',
          indicatorError: '<i class="icon-notification text-danger"></i>',
          indicatorLoading: '<i class="icon-hour-glass text-muted"></i>'
      },
      layoutTemplates: {
          fileIcon: '<i class="icon-file kv-caption-icon"></i> '
      },
      previewZoomButtonIcons: {
          prev: '<i class="icon-backward"></i>',
          next: '<i class="icon-forward"></i>',
          toggleheader: '<i class="icon-enlarge-a"></i>',
          fullscreen: '<i class="icon-enlarge"></i>',
          borderless: '<i class="icon-share"></i>',
          close: '<i class="icon-cross"></i>'
      },
      previewFileIcon: '<i class="icon-file"></i>',
      browseIcon: '<i class="icon-folder-open"></i>',
      removeIcon: '<i class="icon-bin"></i>',
      cancelIcon: '<i class="icon-blocked"></i>',
      uploadIcon: '<i class="icon-upload"></i>',
      msgValidationErrorIcon: '<i class="icon-notification"></i> '
  };
})(window.jQuery);
