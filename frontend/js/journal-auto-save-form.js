JournalAutoSaveForm = function ($element, options) {
  if ($element.data('journalAutoSaveForm') != null) {
    $element.data('journalAutoSaveForm').destroy();
  }

  this.$element = $element;
  this.options = $.extend({}, {
    autoSaveUrl: null,
    delayBeforeSend: 1000,
    delayBetweenSend: 5000,
    delayAlert: 3000
  }, options);

  this.timeoutReference = null;
  this.saveInProcess = false;
  this.dataChanged = false;

  this.$versionToken = this.$element.find('#journal-version_token');
  this.$photos = this.$element.find('#journal-photos');
  this.$goods = this.$element.find('#journal-goods');

  this.registerEvents();

  $element.data('journalAutoSaveForm', this);
};

JournalAutoSaveForm.prototype.destroy = function () {
  this.$element.off('change.journalAutoSaveForm');
  this.$photos.off('PhotoUpload:delete.journalAutoSaveForm');
  this.$goods.off('Goods:change.journalAutoSaveForm');
};

JournalAutoSaveForm.prototype.registerEvents = function () {
  var $this = this;

  this.$element.on('change.journalAutoSaveForm', 'input, select, textarea', function() {
    $this.dataChangedHandler();
  });

  this.$photos.on('PhotoUpload:delete.journalAutoSaveForm', function() {
    $this.dataChangedHandler();
  });

  this.$goods.on('Goods:change.journalAutoSaveForm', function() {
    $this.dataChangedHandler();
  });
};

JournalAutoSaveForm.prototype.dataChangedHandler = function () {
  this.dataChanged = true;
  if (!this.saveInProcess)
    this.save();
};

JournalAutoSaveForm.prototype.save = function () {
  var $this = this;

  if (this.timeoutReference) clearTimeout(this.timeoutReference);
  this.timeoutReference = setTimeout(function(){
    $this.sendDataToServer();
  }, this.options.delayBeforeSend);
};

JournalAutoSaveForm.prototype.sendDataToServer = function () {
  var $this = this;
  //if (!this.timeoutReference) return;
  this.timeoutReference = null;

  if (this.saveInProcess)
    return;
  this.saveInProcess = true;

  this.dataChanged = false;
  var data = this.$element.serialize();

  $.ajax({
    type: "POST",
    url: this.options.autoSaveUrl,
    data: data,
    success: function(data) {
      if ((typeof data != 'undefined') && (typeof data.result != 'undefined')) {
        if (data.result != 'ok') {
          console.log('JournalAutoSaveForm.Save: result=' + data.result + '; msg=' + data.msg);
        }
        else {
          $this.showAlertMsg();
        }
        if (data.version_token)
          $this.refreshVersionToken(data.version_token);
      }
      else {
        console.log('JournalAutoSaveForm.Save: Something wrong!');
      }
    },
    complete: function() {
      setTimeout(function(){
        $this.saveInProcess = false;
        if ($this.dataChanged)
          $this.sendDataToServer();
      }, $this.options.delayBetweenSend)
    }
  });
};

JournalAutoSaveForm.prototype.showAlertMsg = function () {
  var alertHtml =
    '<div class="alert alert-info fade in" style="position: fixed; right: 10px; top: 10px; z-index: 10000;">' +
    '<button type="button" class="close" style="margin-left: 15px" data-dismiss="alert" aria-hidden="true">×</button>' +
    'Запись сохранена' +
    '</div>';
  var $alert = $(alertHtml).appendTo('body');

  setTimeout(function(){
    $alert.find('.close').click();
  }, this.options.delayAlert);
};

JournalAutoSaveForm.prototype.refreshVersionToken = function (dataVersionToken) {
  this.$versionToken.val(dataVersionToken);
};

if ($.fn.journalAutoSaveForm == null) {
  $.fn.journalAutoSaveForm = function (options) {
    options = options || {};

    if (typeof options === 'object') {
      this.each(function () {
        var instanceOptions = $.extend(true, {}, options);

        var instance = new JournalAutoSaveForm($(this), instanceOptions);
      });

      return this;
    } else {
      throw new Error('Invalid arguments for JournalAutoSaveForm: ' + options);
    }
  };
}