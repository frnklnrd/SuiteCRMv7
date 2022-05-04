/*maguilar: funcion para desplegar el div de filtros*/
function displayFilters() {
    $('#kanban-filters').slideToggle();
}

$(document).ready(function () {
    $(document).on("scroll", function (e) {
        $('.kanban-board tr.line').each(function (index, element) {
            var id_header = $(element).attr('id');
            var offset = $(element).position().top - 100;
            var height = $(element).height() - 80;
            var endoffset = offset + height;
            var scroll_value = $(document).scrollTop();

            if (scroll_value > offset && scroll_value < endoffset) {
                //console.log("SCROLL IN; scroll_value:"+scroll_value +" ["+offset +","+ endoffset+"] height: "+height );
                $('h1[id=' + id_header + '_header]').addClass("fix-header");
                $(element).siblings('.kanban-board-header').addClass('fix-header-kanban');
                $(element).siblings('.kanban-board-header').css(
                    'width',
                    $(element).get(0).offsetWidth
                );
            } else {
                //console.log("SCROLL OUT; scroll_value:"+scroll_value +" ["+offset +","+ endoffset+"] height: "+height );
                //console.log($('h1[id=' + id_header + '_header]'));
                $('h1[id=' + id_header + '_header]').removeClass("fix-header");
                $(element).siblings('.kanban-board-header').removeClass('fix-header-kanban');
            }
            if (scroll_value == 0) {
                $('h1[id=' + id_header + '_header]').removeClass("fix-header");
                $(element).siblings('.kanban-board-header').removeClass('fix-header-kanban');
            }

        });
    });
});

/*fin maguilar*/
$(function () {
    // aromero: fixed header

    var Kanban = new function () {
        this.actions = {};
        this.window = $(window);
        this.sidebar = $('.sidebar');
        this.board = $('#kanban-board');
        this.closeBoard = $('#kanban-board-close');
        this.objectContainers = $('td.column');
        this.form = $('#kanban-form');
        this.contextMenu = $('#kanban-panel-menu-items');
        this.context = null;
        this.current = null;
        this.originlist = null;
        this.targetlist = null;
        self = this;

        this.init = function () {

            this.prepareForm();
            this.prepareKanbanBoard();
            this.bindEvents();
            this.resize();

            //-------------------------------------------------------
            // PW - custom code

            $('#kaban-nav > span:first-child').append('<span class="myArea">|</span>' +
                '<select id="kanban-filter-by-horizontal-division-rule" style="margin-left: 15px;">' +
                '<option value="">---------------------------------</option>' +
                '</select>');

            $('.kanban-board-horizontal > h1 > a').each(function (id, el) {
                var text = $(this).text();
                $('#kanban-filter-by-horizontal-division-rule').append('<option value="' + text + '">' + text + '</option>');
            });

            $('#kaban-nav').append('<hr style="border: 1px solid #8f8787;margin-bottom: 0px;"/>');

            $(document).on('change', '#kanban-filter-by-horizontal-division-rule', function (event) {

                var key = $(this).val();

                if ($(this).val() == "") {
                    $('.kanban-board-horizontal').show();
                }

                else {
                    $('.kanban-board-horizontal').hide();

                    $('.kanban-board-horizontal > h1 > a:contains("' + key + '")').closest('.kanban-board-horizontal').show();
                }
            });

            //$('#kanban-filter-by-horizontal-division-rule').trigger('change');
            //-------------------------------------------------------

        };

        this.prepareForm = function () {
            dialog_buttons = {
                'OK': function () {
                    if (self.form.type == 'view') {
                        self.form.dialog('close');
                        return;
                    }
                    if (check_form('EditView')) {
                        var form;

                        form = $('#EditView');
                        form.find('input[name="action"]').val('Save');

                        self.actions.sendForm(form).done(function () {
                            self.form.dialog('close');
                            self.form.empty();

                            if (self.context.record) {
                                self.updateCurrent();
                            } else {
                                self.updateColumn();
                            }
                            location.reload(true);
                        });
                    }
                }
            }
            dialog_buttons[SUGAR.language.get('Kanban', 'LBL_MODAL_CLOSE_BUTTON')] = function () {
                self.form.dialog('close');
            }

            self.form.dialog({
                width: 'auto',
                maxHeight: 600,
                modal: true,
                autoOpen: false,
                dialogClass: 'kanban-form-container',
                buttons: dialog_buttons
            });
        };

        this.updateCurrent = function () {

            SUGAR.ajaxUI.showLoadingPanel();
            self.actions.do('read').done(function (response) {
                self.objectToCurrent(response);
                location.reload();
            });
            SUGAR.ajaxUI.hideLoadingPanel();
        };

        this.updateColumn = function () {
            SUGAR.ajaxUI.showLoadingPanel();
            self.actions.do('read-column').done(function (response) {
                var el;
                $.each(response.items, function (key, item) {
                    el = $('div[data-id="' + item.id + '"][data-module="' + item.module + '"]');
                    if (!el.length) {
                        self.addItem(item);
                    }
                });
                self.prepareKanbanBoard();
            });
            SUGAR.ajaxUI.hideLoadingPanel();
        };

        this.toggleMenu = function (event) {
            self.contextMenu.menu();
            self.contextMenu.css({
                position: 'fixed',
                top: event.pageY,
                left: event.pageX
            })
            self.contextMenu.toggle();
        };

        this.detailObject = function () {
            window.open('index.php?action=DetailView&module=' + self.context.module + '&record=' + self.context.record, '_blank');
        };

        this.prepareKanbanBoard = function () {
            $(".accordion").accordion({
                active: false,
                collapsible: true,
                heightStyle: "content"
            });
            $('.kanban-board .line').each(function (index, element) {
                var class_sort = $(element).attr('id');
                $("div.sortable." + class_sort).sortable({
                    revert: false,
                    placeholder: 'drag-place-holder',
                    forcePlaceholderSize: true,
                    connectWith: "." + class_sort,
                    helper: function (event, element) {
                        return $(element).clone().addClass('dragging');
                    },
                    start: function (e, ui) {
                        self.closeBoard.css({
                            height: '200px',
                            left: $('#sidebar_container > div.sidebar').length > 0 ? $('#sidebar_container > div.sidebar')[0].offsetWidth : 0,
                            right: 20,
                            bottom: 0,
                            visibility: 'visible',
                        });
                        //self.closeBoard.show('fade');
                        self.contextMenu.hide();
                        ui.item.show().addClass('ghost');
                    },
                    stop: function (e, ui) {
                        self.current = ui.item;

                        var boardType = ui.item.closest('table').closest('div').attr('id');
                        var container = ui.item.closest('div.sortable');

                        //self.closeBoard.hide('fade');
                        self.closeBoard.css({visibility: 'hidden'});
                        // aromero: Obtengo y almaceno las listas despues del cambio
                        var originlist = $(this).sortable("toArray", {attribute: 'data-id'});
                        var targetlist = container.sortable("toArray", {attribute: 'data-id'});
                        self.originlist = originlist;
                        self.targetlist = targetlist;

                        ui.item.find('.kanban-panel-title').append('<span class="kanban-panel-title-loading" ></span>');
                        var loading = ui.item.find('.kanban-panel-title-loading');
                        self.actions.do('drop').done(function (response) {
                            self.objectToCurrent(response);
                            loading.hide();
                        });
                        ui.item.show().removeClass('ghost');

                        $(ui.item).closest('div.sortable').trigger('kanban-panel.item-moved');

                        if (boardType === 'kanban-board-close') {
                            container.empty();
                            container.text(container.data('title'));
                        }
                    },
                    cursor: 'move'
                });
            });

            $('#kanban-board table').disableSelection();
        };

        this.objectToCurrent = function (response) {
            self.current.find('.kanban-panel-title').html(response.title);
            self.current.find('.kanban-panel-body-text').html(response.content);

            var mark = self.current.find('.kanban-panel-body-mark');

            mark.removeClass();
            mark.addClass('kanban-panel-body-mark');
            mark.addClass('mark-' + response.mark);
            $(".accordion").accordion({
                active: false,
                collapsible: true
            });
            self.current.find('.kanban-panel-heading').css('background-color', response.kanban_header_color);

            $(self.current).trigger('kanban-panel.item-added');
        };

        this.setContext = function (context) {
            self.current = $(context);
            self.context = {
                module: $(context).data('module'),
                record: $(context).data('id'),
                name: $(context).data('name')
            };
        };

        this.bindEvents = function () {
            // aromero: cambio click por dblclick para permitir un solo click en enlaces dentro del body.
            $('body').on('dblclick', '.kanban-panel', function (event) {
                self.setContext(this);
                self.actions.editObject();
            });

            $('body').on('contextmenu', '.kanban-panel', function (event) {
                event.preventDefault();
                self.contextMenu.hide();
                self.setContext(this);
                self.toggleMenu(event);
            });

            $('#kanban-board table > tbody > tr > th').on('dblclick', function () {
                self.current = $(this);
                self.context = {
                    module: $(this).data('module')
                };
                self.actions.createObject();
            });

            $('#kanban,#kanban-board,.container-fluid').on('click', function () {
                self.contextMenu.hide();
                $('#kanban-modal').hide();
            });

            $('#buttontoggle').on('click', function () {
                self.resize();
            });

            $(window).resize(function () {
                self.resize();
            });

            $('#kanban-modal-link').on('click', function () {
                $('#kanban-modal').css({
                    left: this.getBoundingClientRect().x,
                    top: this.getBoundingClientRect().y
                });
                $('#kanban-modal').show();
            });

            $('#kanban-modal-close').on('click', function () {
                $('#kanban-modal').hide();
            });

            $('.kanban-panel-menu-items-create-task').on('click', function () {
                self.actions.createRelatedObject({
                    module: 'Tasks'
                });
            });

            $('.kanban-panel-menu-items-create-meeting').on('click', function () {
                self.actions.createRelatedObject({
                    module: 'Meetings'
                });
            });

            $('.kanban-panel-menu-items-create-call').on('click', function () {
                self.actions.createRelatedObject({
                    module: 'Calls'
                });
            });

            $('.kanban-panel-menu-items-create-mission').on('click', function () {
                self.actions.createRelatedObject({
                    module: 'Missions'
                });
            });

            $('.kanban-panel-menu-items-details').on('click', function () {
                self.contextMenu.hide();
                self.detailObject();
            });

            $('.kanban-panel-menu-items-plans').on('click', function () {
                self.actions.showSubpanel('activities');
            });

            $('.kanban-panel-menu-items-history').on('click', function () {
                self.actions.showSubpanel('history');
            });
            // aromero: link personalizado
            $('.kanban-panel-menu-items-mylink').on('click', function () {
                alert("añadir aquí la redirección a donde se quiera.");
            });
        };

        this.column = function () {
            var container = self.current.closest('th');

            if (!container.length) {
                container = self.current.closest('div.sortable');
            }
            return {
                field: container.data('field'),
                value: container.data('value')
            };
        };

        this.resize = function () {
            var windowW = self.window.width();
            var windowH = self.window.height();
            var sidebarW = 0;
            var columns = this.closeBoard.find('td');

            if (this.sidebar.is(':visible')) {
                //sidebarW = this.sidebar.width() + 50;
            }

            //this.board.height(windowH - 100);
            this.objectContainers.height(this.board.height() - this.sidebar.height());
            //this.board.width(windowW - 50 - sidebarW);
            columns.width(this.closeBoard.width() / columns.length);
            // aromero: misma anchura para todas las columnas y accordions
            $(".line").each(function (index, element) {
                var num_columns = element.cells.length;
                var line_width = $(element).width();
                $(".column").css('width', (line_width / num_columns));
            });
            var width_accordion = $(".accordion").width();
            $(".accordion").css('width', width_accordion);

        };

        this.addItem = function (item) {
            var html = '<div class="kanban-panel ui-state-default" data-module="' +
                item.module + '" data-id="' +
                item.id + '" data-name="' +
                item.name + '" data-kanban="' + item.record + '">' +
                '<div class="kanban-panel-heading">' +
                '<h3 class="kanban-panel-title">' +
                item.title +
                '</h3>' +
                '</div>' +
                '<div class="kanban-panel-body">' +
                '<table width="100%" height="100%">' +
                '<tr>' +
                '<td valign="middle" class="kanban-panel-body-text">' +
                item.content +
                '</td>' +
                '<td valign="middle" align="center" class="kanban-panel-body-mark mark-' + item.mark + '">'
            '</td>' +
            '</tr>' +
            '</table>' +
            '</div>' +
            '</div>';
            $('div.sortable[data-value="' + self.column().value + '"]').append(html);
            $('div.sortable[data-value="' + self.column().value + '"]').trigger('kanban-panel.item-added');
        }

        this.drawForm = function (html) {
            self.contextMenu.hide();

            self.form.html(html);
            self.form.find('.dcQuickEdit,.action_buttons,div.buttons').hide();
            self.form.dialog('open');

            $(".ui-dialog-titlebar").hide();
            $('#EditView').find('select[name="' + self.column().field + '"]').hide();
        };

        this.actions.editObject = function () {
            return $.ajax({
                dataType: 'html',
                url: 'index.php?' + $.param({
                    module: 'Kanban',
                    action: 'ajaxform',
                    target: self.context
                })
            }).done(function (response) {
                self.form.type = 'edit';
                self.drawForm(response);
            });
        };

        this.actions.createObject = function () {
            var query = {
                module: 'Kanban',
                action: 'ajaxform',
                target: self.context
            };

            query[self.column().field] = self.column().value;

            $.ajax({
                dataType: 'html',
                url: 'index.php?' + $.param(query)
            }).done(function (response) {
                self.form.type = 'edit';
                self.drawForm(response);
            });
        };

        this.actions.sendForm = function (form) {
            return $.ajax({
                data: $(form).serializeArray(),
                type: 'POST',
                url: 'index.php'
            });
        };

        this.actions.do = function (action) {
            return $.ajax({
                dataType: 'json',
                url: 'index.php?' + $.param({
                    module: 'Kanban',
                    action: action,
                    record: self.current.data('kanban'),
                    target: {
                        module: self.current.data('module'),
                        record: self.current.data('id'),
                        field: self.column().field,
                        value: self.column().value,
                    },
                    // aromero: añado las listas como deben quedar.
                    originlist: self.originlist,
                    targetlist: self.targetlist,
                })
            });
        };

        this.actions.showSubpanel = function (subpanel_type) {
            var query = {
                module: 'Kanban',
                action: 'ajaxform',
                target: self.context,
                view: 'subpanel',
                subpanel_type: subpanel_type
            };

            return $.ajax({
                dataType: 'html',
                url: 'index.php?' + $.param(query)
            }).done(function (response) {
                self.form.type = 'view';
                self.drawForm(response);
            });
        };

        this.actions.createRelatedObject = function (targetArray) {
            var query = {
                module: 'Kanban',
                action: 'ajaxform',
                target: targetArray,
                parent_type: self.context.module,
                parent_id: self.context.record,
                parent_name: self.context.name
            };

            $.ajax({
                dataType: 'html',
                url: 'index.php?' + $.param(query)
            }).done(function (response) {
                self.form.type = 'edit';
                self.drawForm(response);
            });
        };
    };
    Kanban.init();
});