define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'hotel/index' + location.search,
                    add_url: 'hotel/add',
                    edit_url: 'hotel/edit',
                    del_url: 'hotel/del',
                    multi_url: 'hotel/multi',
                    table: 'hotel',
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
                        {field: 'hotelGroupId', title: __('Hotelgroupid')},
                        {field: 'code', title: __('Code')},
                        {field: 'descript', title: __('Descript'),align: 'left',formatter: Controller.api.formatter.room},
                        {field: 'country', title: __('Country')},
                        {field: 'city', title: __('City')},
                        {field: 'address1', title: __('Address1')},
                        {field: 'phone', title: __('Phone')},
                        {field: 'cityCode', title: __('Citycode')},
                        {
                            field: 'operate', 
                            title: __('Operate'), 
                            table: table, 
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'images',
                                    title: __('上传图集'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-file-picture-o',
                                    text: '图集',
                                    url: 'hotel/images'
                                },
                                {
                                    name: 'background',
                                    title: __('上传背景图'),
                                    text: '背景图',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: '背景图',
                                    url: 'hotel/background'
                                },
                                {
                                    name: 'background',
                                    title: __('上传Banner图'),
                                    text: 'Banner图',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: '背景图',
                                    url: 'hotel/banner'
                                }
                            ]
                        }
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
        images: function() {
            Controller.api.bindevent();
        },
        background: function() {
            Controller.api.bindevent();
        },
        banner: function() {
            Controller.api.bindevent();
        },

        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                room: function (value, row, index) {
                    //这里手动构造URL
                    url = "room/index?hotel_id=" + row.id;

                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    return '<a href="' + url + '" class="label label-success addtabsit" title="' + __(value) + '">' + __(value) + '</a>';

                    //方式二,直接调用Table.api.formatter.addtabs
                    return Table.api.formatter.addtabs(value, row, index, url);
                }
            }
        }
    };
    return Controller;
});