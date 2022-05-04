({
    extendsFrom: 'RecordlistView',

    render: function() {
        var app = window.parent.SUGAR.App;
        var params = window.location.href.split('/');
        var url = "#bwc/index.php?module=Kanban&action=index";

        var index = params.indexOf('#Kanban');
        if (index < 0) {
            index = params.indexOf('index.php#Kanban');
        }

        if (index >= 0) {
            var record = params[index + 1];

            if (record !== undefined) {
                if (params.indexOf('EditView') > 0) {
                    url = "#bwc/index.php?module=Kanban&action=editview&record=" + record;
                } else if (params.indexOf('create') > 0) {
                    url = "#bwc/index.php?module=Kanban&action=editview";
                } else {
                    url = "#bwc/index.php?module=Kanban&action=detailview&record=" + record;
                }
            }
        }

        window.parent.SUGAR.App.sync({callback: function(){
            app.router.navigate(url, {trigger:true});
        }});
    }
})