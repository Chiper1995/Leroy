function Dialogs() {
    var $this = this;

    this.submitTimeout = undefined;

    this.form = undefined;

    jQuery(document).ready(function () {

        $this.form = $('#dialogFilterForm');

        $('#mydialogssearch-users_id').on('change', function(){
            if ($this.submitTimeout != undefined) {
                clearTimeout($this.submitTimeout);
                $this.submitTimeout = undefined;
            }
            $this.submitTimeout = setTimeout(function(){$($this.form).submit();}, 1500);
        });
    });
}
