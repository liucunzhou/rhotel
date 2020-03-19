define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'room/index' + location.search,
                    add_url: 'room/add',
                    edit_url: 'room/edit',
                    del_url: 'room/del',
                    multi_url: 'room/multi',
                    table: 'room',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'hotel_id', title: __('Hotel_id')},
                        {field: 'title', title: __('Title')},
                        {field: 'titlepic', title: __('Titlepic'),formatter: Table.api.formatter.image},
                        {field: 'area', title: __('Area')},
                        {field: 'floor', title: __('Floor')},
                        {field: 'bed', title: __('Bed')},
                        {field: 'window', title: __('Window')},
                        {field: 'other', title: __('Other')},
                        {field: 'sort', title: __('Sort')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});