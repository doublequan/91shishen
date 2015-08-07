var feedbackIcons = {
    valid: 'glyphicon glyphicon-ok',
    invalid: 'glyphicon glyphicon-remove',
    validating: 'glyphicon glyphicon-refresh'
};
var username_regexp_str = /^[a-zA-Z0-9]{6,20}$/;
var phone_regexp_str = /((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/;
var email_regexp_str = /^[\._\-a-zA-Z0-9]+@([_a-zA-Z0-9]+\.)+[a-zA-Z0-9]{2,3}$/;

var validate_rules = {
    product: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            sku: {
                validators: {
                    notEmpty: {
                        message: '请填写商品编号'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 16,
                        message: '商品编号长度为6-16个字符'
                    },
                }
            },
            product_pin: {
                validators: {
                    notEmpty: {
                        message: '请填写商品货号'
                    },
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '请填写商品名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 200,
                        message: '商品名称长度格式错误'
                    },
                }
            },
            category_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属分类'
                    },
                }
            },
            good_name: {
                validators: {
                    notEmpty: {
                        message: '请选择所属原料'
                    },
                }
            },
            good_num: {
                validators: {
                    notEmpty: {
                        message: '请填写原料使用数量'
                    },
                }
            },
            price: {
                validators: {
                    notEmpty: {
                        message: '请填写商品销售价'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 17,
                        message: '商品销售价长度格式错误'
                    },
                }
            },
            price_market: {
                validators: {
                    notEmpty: {
                        message: '请填写商品市场价'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 17,
                        message: '商品市场价长度格式错误'
                    },
                }
            },
            spec: {
                validators: {
                    notEmpty: {
                        message: '请填写商品规格'
                    },
                }
            },
            spec_packing: {
                validators: {
                    notEmpty: {
                        message: '请填写商品包装规格'
                    },
                }
            },
            unit: {
                validators: {
                    notEmpty: {
                        message: '请填写商品单位'
                    },
                }
            },
        }
    },
    good: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写原料名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 255,
                        message: '原料名称长度格式错误'
                    },
                }
            },
            category_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属分类'
                    },
                }
            },
            unit: {
                validators: {
                    notEmpty: {
                        message: '请选择原料计价单位'
                    },
                }
            },
        }
    },
    dispatch: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            out_store: {
                validators: {
                    notEmpty: {
                        message: '请选择出货门店'
                    },
                }
            },
            in_store: {
                validators: {
                    notEmpty: {
                        message: '请选择入库门店'
                    },
                }
            },
        }
    },
    purchase: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            type: {
                validators: {
                    notEmpty: {
                        message: '请选择采购类型'
                    },
                }
            },
            in_prov: {
                validators: {
                    notEmpty: {
                        message: '请选择省/直辖市'
                    },
                }
            },
            in_city: {
                validators: {
                    notEmpty: {
                        message: '请选择入库门店所属城市'
                    },
                }
            },
            store_id: {
                validators: {
                    notEmpty: {
                        message: '请选择入库门店'
                    },
                }
            },
            checkout_type: {
                validators: {
                    notEmpty: {
                        message: '请选择支付方式'
                    },
                }
            },
            price_borrow: {
                validators: {
                    notEmpty: {
                        message: '请填写预借金额'
                    },
                }
            },
            price_fee: {
                validators: {
                    notEmpty: {
                        message: '请填写运费金额'
                    },
                }
            },
        }
    },
    purchase_finance: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            money_apply: {
                validators: {
                    notEmpty: {
                        message: '请填写申请金额'
                    },
                    numeric: {
                        message: '请填写数字'
                    },
                }
            },
        }
    },
    stock_add: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            in_prov: {
                validators: {
                    notEmpty: {
                        message: '请选择入库门店所属省/直辖市'
                    },
                }
            },
            in_city: {
                validators: {
                    notEmpty: {
                        message: '请选择入库门店所属城市'
                    },
                }
            },
            in_store: {
                validators: {
                    notEmpty: {
                        message: '请选择入库门店'
                    },
                }
            },
        }
    },
    stock_deal: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            store_id: {
                validators: {
                    notEmpty: {
                        message: '请选择门店'
                    },
                }
            },
            action: {
                validators: {
                    notEmpty: {
                        message: '请选择变化类型'
                    },
                }
            },
            amount: {
                validators: {
                    notEmpty: {
                        message: '请填写变化数量'
                    },
                }
            },
        }
    },
    loss: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            type_id: {
                validators: {
                    notEmpty: {
                        message: '请选择损耗类型'
                    },
                }
            },
            t: {
                validators: {
                    notEmpty: {
                        message: '请选择产品类型'
                    },
                }
            },
            'common_name[]': {
                validators: {
                    notEmpty: {
                        message: '请填写名称'
                    },
                }
            },
            amount_total: {
                validators: {
                    notEmpty: {
                        message: '请填写总量'
                    },
                }
            },
            amount_loss: {
                validators: {
                    notEmpty: {
                        message: '请填写损失量'
                    },
                }
            },
            price: {
                validators: {
                    notEmpty: {
                        message: '请填写单品采购价'
                    },
                }
            },
            employee_name: {
                validators: {
                    notEmpty: {
                        message: '请选择责任员工'
                    },
                }
            },
        }
    },

    category: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写分类名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 100,
                        message: '分类名称长度格式错误'
                    },
                }
            },
            site_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属网站'
                    },
                }
            },
            father_id: {
                validators: {
                    notEmpty: {
                        message: '请选择上级分类'
                    },
                }
            },
        }
    },
    spec: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写规格名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '规格名称格式错误'
                    },
                }
            },
        }
    },
    brand: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写品牌名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 255,
                        message: '品牌名称长度格式错误'
                    },
                }
            },
        }
    },
    loss_type: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写损耗类型'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 100,
                        message: '损耗类型长度格式错误'
                    },
                }
            },
        }
    },

    vip_product: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            sku: {
                validators: {
                    notEmpty: {
                        message: '请填写商品SKU'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 20,
                        message: 'SKU长度格式错误'
                    },
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '请填写商品名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 200,
                        message: '商品名称长度格式错误'
                    },
                }
            },
            category_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属分类'
                    },
                }
            },
            price: {
                validators: {
                    notEmpty: {
                        message: '请填写商品销售价'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 17,
                        message: '商品销售价长度格式错误'
                    },
                }
            },
            spec: {
                validators: {
                    notEmpty: {
                        message: '请填写商品规格'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '商品规格长度格式错误'
                    },
                    
                }
            },
            spec_packing: {
                validators: {
                    notEmpty: {
                        message: '请填写商品包装规格'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '商品包装规格长度格式错误'
                    },
                    
                }
            },
            unit: {
                validators: {
                    notEmpty: {
                        message: '请填写商品计量单位'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '商品计量单位长度格式错误'
                    },
                    
                }
            },
        }
    },
    vip_custom_product: {
        message: '输入格式错误，请检查',
        fields: {
            company_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属大客户公司'
                    },
                }
            },
            uid: {
                validators: {
                    notEmpty: {
                        message: '请选择所属大客户用户'
                    },
                }
            },
            'name[]': {
                validators: {
                    notEmpty: {
                        message: '请填写商品名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 255,
                        message: '商品名称长度格式错误'
                    },
                }
            },
            'amount[]': {
                validators: {
                    notEmpty: {
                        message: '请填写商品数量'
                    },
                    numeric: {
                        message: '请填写数字'
                    },
                }
            },
            'unit[]': {
                validators: {
                    notEmpty: {
                        message: '请填写商品单位'
                    },
                }
            },
        }
    },
    vip_price: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            price: {
                validators: {
                    notEmpty: {
                        message: '请填写修改后的价格'
                    },
                    numeric: {
                        message: '请填写数字'
                    },
                }
            },
        }
    },
    vip_industry: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写行业名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 100,
                        message: '行业名称长度格式错误'
                    },
                }
            },
        }
    },
    vip_company: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写客户公司名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 3,
                        max: 200,
                        message: '客户公司名称长度有误'
                    },
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: '请填写客户公司地址'
                    },
                    stringLength: {
                        enabled: true,
                        min: 3,
                        max: 200,
                        message: '客户公司地址长度有误'
                    },
                }
            },
            prov: {
                validators: {
                    notEmpty: {
                        message: '请选择所属省区'
                    },
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: '请选择所属市'
                    },
                }
            },
            tel: {
                validators: {
                    notEmpty: {
                        message: '请填写客户公司电话'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '公司电话不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '请输入格式正确的电话号码'
                    }
                }
            },
            industry_id: {
                validators: {
                    notEmpty: {
                        enabled: true,
                        message: '请选择客户公司所属行业'
                    },
                }
            },
            scale: {
                validators: {
                    notEmpty: {
                        enabled: true,
                        message: '请选择客户公司规模'
                    },
                }
            },
        }
    },
    vip_user_add: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: '请填写用户名'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '用户名不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: username_regexp_str,
                        message: '用户名在6-20位并且仅能使用字母和数字'
                    }
                }
            },
            company_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属公司'
                    },
                }
            },
            discount: {
                validators: {
                    notEmpty: {
                        message: '请填写用户默认折扣率'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^\d{1,3}$/,
                        message: '用户默认折扣率格式错误'
                    }
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写用户手机号码'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^\d{11}$/,
                        message: '用户手机号码格式错误'
                    }
                }
            },
            pass: {
                validators: {
                    notEmpty: {
                        message: '请填写用户登陆密码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 32,
                        message: '用户登陆密码不能少于6位'
                    },
                }
            },
            repass: {
                validators: {
                    notEmpty: {
                        message: '请填写用户登陆密码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 32,
                        message: '用户登陆密码不能少于6位'
                    },
                }
            },
        }
    },
    vip_user_edit: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: '请填写用户名'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '用户名不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: username_regexp_str,
                        message: '用户名在6-20位并且仅能使用字母和数字'
                    }
                }
            },
            company_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属公司'
                    },
                }
            },
            discount: {
                validators: {
                    notEmpty: {
                        message: '请填写用户默认折扣率'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^\d{1,3}$/,
                        message: '用户默认折扣率格式错误'
                    }
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写用户手机号码'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^\d{11}$/,
                        message: '用户手机号码格式错误'
                    }
                }
            },
        }
    },
    vip_category: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写分类名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 2,
                        max: 100,
                        message: '分类名称不能大于100字符或小于2个字符'
                    },
                }
            },
            thumb: {
                validators: {
                    notEmpty: {
                        message: '请选择所属公司'
                    },
                }
            },
            sort: {
                validators: {
                    regexp: {
                        enabled: true,
                        regexp: /^\d{1,2}$/,
                        message: '排序值需输入数字且小于100'
                    }
                }
            },
        }
    },

	company: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写公司名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 3,
                        max: 200,
                        message: '公司名称长度不能大于200字符或小于3个字符'
                    },
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: '请填写公司地址'
                    },
                    stringLength: {
                        enabled: true,
                        min: 3,
                        max: 200,
                        message: '公司地址长度不能大于200字符或小于3个字符'
                    },
                }
            },
            province_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属省区'
                    },
                }
            },
            city_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属市'
                    },
                }
            },
            // phone: {
            //     validators: {
            //         notEmpty: {
            //             message: '请填写公司电话'
            //         },
            //         stringLength: {
            //             enabled: true,
            //             min: 6,
            //             max: 20,
            //             message: '公司电话不能大于20字符或小于6个字符'
            //         },
            //         regexp: {
            //             enabled: true,
            //             regexp: phone_regexp_str,
            //             message: '请输入格式正确的电话号码'
            //         }
            //     }
            // },
            // employee_name: {
            //     validators: {
            //         notEmpty: {
            //             enabled: true,
            //             message: '请填写公司经理'
            //         },
            //         stringLength: {
            //             enabled: true,
            //             min: 2,
            //             max: 10,
            //             message: '公司经理不能大于10字符或小于2个字符'
            //         },
                    
            //     }
            // },
        }
    },
    site: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写网站名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 250,
                        message: '网站名称格式错误'
                    },
                }
            },
            sort: {
                validators: {
                    notEmpty: {
                        message: '请填写网站排序'
                    },
                }
            },
            prov: {
                validators: {
                    notEmpty: {
                        message: '请选择网站所属省份'
                    },
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: '请选择网站所属城市'
                    },
                }
            },
            default_store: {
                validators: {
                    notEmpty: {
                        message: '请选择默认网站'
                    },
                }
            },
        }
    },
    department: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            company_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属公司'
                    },
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写部门名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '部门名称长度有误'
                    },
                }
            },
        }
    },
    store: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写门店名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 100,
                        message: '门店名称格式错误'
                    },
                }
            },
            site_id: {
                validators: {
                    notEmpty: {
                        message: '请选择门店所属网站'
                    },
                }
            },
            employee_name: {
                validators: {
                    notEmpty: {
                        message: '请选择门店负责人'
                    },
                }
            },
            tel: {
                validators: {
                    notEmpty: {
                        message: '请选择门店负责人'
                    },
                },
                regexp: {
                    enabled: true,
                    regexp: phone_regexp_str,
                    message: '请输入格式正确的电话号码'
                }
            },
            prov: {
                validators: {
                    notEmpty: {
                        message: '请选择门店所在省/直辖市'
                    },
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: '请选择门店所在城市'
                    },
                }
            },
            district: {
                validators: {
                    notEmpty: {
                        message: '请选择门店所在区县'
                    },
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: '请填写门店地址'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 250,
                        message: '门店地址格式错误'
                    },
                }
            },
        }
    },

    employee_add: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            account: {
                validators: {
                    notEmpty: {
                        message: '请输入员工登录名！'
                    },
                    stringLength: {
                        min: 3,
                        max: 20,
                        message: '登录名长度错误'
                    },
                    regexp: {
                        regexp: /^[a-z]{3,18}[0-9]{0,2}$/,
                        message: '登录名以英文开头且仅包含英文字母和数字'
                    }
                }
            },
            username: {
                validators: {
                    notEmpty: {
                        message: '请填写员工姓名'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 10,
                        message: '员工姓名长度格式错误'
                    },
                    regexp: {
                        regexp: /^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]){2,20}$/,
                        message: '员工姓名仅支持中文'
                    }
                }
            },
            pass: {
                validators: {
                    notEmpty: {
                        message: '请填写员工登陆密码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 9999,
                        message: '员工登陆密码不能少于6位'
                    },
                }
            },
            repass: {
                validators: {
                    notEmpty: {
                        message: '请重新输入员工登陆密码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 9999,
                        message: '员工登陆密码不能少于6位'
                    },
                }
            },
            gender: {
                validators: {
                    notEmpty: {
                        message: '请选择员工性别'
                    },
                }
            },
            idcard: {
                validators: {
                    notEmpty: {
                        message: '请填写员工身份证号'
                    },
                    stringLength: {
                        enabled: true,
                        min: 15,
                        max: 18,
                        message: '员工身份证号格式错误'
                    },
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写员工手机号'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '手机号不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '请输入格式正确的手机号码'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: '请填写员工邮箱'
                    },
                    emailAddress: {
                        message: '员工邮箱格式错误'
                    }
                }
            },
            company_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属公司'
                    },
                }
            },
            dept_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属部门'
                    },
                }
            },
            'role_ids[]': {
                validators: {
                    notEmpty: {
                        message: '请分配角色'
                    },
                }
            },
        }
    },
    employee_edit: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            account: {
                validators: {
                    notEmpty: {
                        message: '请输入员工登录名！'
                    },
                    stringLength: {
                        min: 3,
                        max: 20,
                        message: '登录名长度错误'
                    },
                    regexp: {
                        regexp: /^[a-z]{3,18}[0-9]{0,2}$/,
                        message: '登录名以英文开头且仅包含英文字母和数字'
                    }
                }
            },
            username: {
                validators: {
                    notEmpty: {
                        message: '请填写员工姓名'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 10,
                        message: '员工姓名长度格式错误'
                    },
                    regexp: {
                        regexp: /^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]){2,20}$/,
                        message: '员工姓名仅支持中文'
                    }
                }
            },
            gender: {
                validators: {
                    notEmpty: {
                        message: '请选择员工性别'
                    },
                }
            },
            idcard: {
                validators: {
                    notEmpty: {
                        message: '请填写员工身份证号'
                    },
                    stringLength: {
                        enabled: true,
                        min: 15,
                        max: 18,
                        message: '员工身份证号格式错误'
                    },
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写员工手机号'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '手机号不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '请输入格式正确的手机号码'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: '请填写员工邮箱'
                    },
                    emailAddress: {
                        message: '员工邮箱格式错误'
                    }
                }
            },
            company_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属公司'
                    },
                }
            },
            dept_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属部门'
                    },
                }
            },
            'role_ids[]': {
                validators: {
                    notEmpty: {
                        message: '请分配角色'
                    },
                }
            },
        }
    },
    supplier: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            sup_name: {
                validators: {
                    notEmpty: {
                        message: '请填写供应商姓名'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 30,
                        message: '供应商姓名格式错误'
                    },
                }
            },
            sup_phone: {
                validators: {
                    notEmpty: {
                        message: '请填写供应商电话号码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '电话号码不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '请输入格式正确的电话号码'
                    }
                }
            },
            contact_name: {
                validators: {
                    notEmpty: {
                        message: '请填写供应商联系人'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 20,
                        message: '供应商联系人姓名格式错误'
                    },
                }
            },
            contact_mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写供应商联系人手机'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '电话号码不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '请输入格式正确的电话号码'
                    }
                }
            },
            contact_email: {
                validators: {
                    notEmpty: {
                        message: '请填写供应商联系人邮箱'
                    },
                    emailAddress: {
                        message: '供应商联系人邮箱格式错误'
                    }
                }
            },
        }
    },
    
    permission_group: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写权限组名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '权限组名称格式错误'
                    },
                }
            },
            key: {
                validators: {
                    notEmpty: {
                        message: '请填写权限组KEY'
                    },
                    stringLength: {
                        enabled: true,
                        min: 3,
                        max: 20,
                        message: '权限组KEY格式错误'
                    },
                }
            },
        }
    },
    permission: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写权限名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 1,
                        max: 50,
                        message: '权限名称格式错误'
                    },
                }
            },
            key: {
                validators: {
                    notEmpty: {
                        message: '请填写权限KEY'
                    },
                    stringLength: {
                        enabled: true,
                        min: 3,
                        max: 20,
                        message: '权限KEY格式错误'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^[A-Za-z\_\/]*$/,
                        message: '权限KEY格式错误'
                    },
                }
            },
            group_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属权限组'
                    },
                }
            },
        }
    },
    permission_role: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写角色名称'
                    },
                    stringLength: {
                        enabled: true,
                        min: 2,
                        max: 50,
                        message: '角色名称格式错误'
                    },
                }
            },
        }
    },
	user_add: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: '请填写用户名'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '用户名不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: username_regexp_str,
                        message: '用户名在6-20位并且仅能使用字母和数字'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: '请填写用户邮箱'
                    },
                    regexp: {
                        enabled: true,
                        regexp: email_regexp_str,
                        message: '用户邮箱格式错误'
                    }
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写用户手机号码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 11,
                        max: 11,
                        message: '用户手机号码应为11个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '用户手机号码格式错误'
                    }
                }
            },
            pass: {
                validators: {
                    notEmpty: {
                        message: '请填写用户登陆密码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 10000,
                        message: '用户登陆密码不能少于6位'
                    },
                }
            },
            repass: {
                validators: {
                    notEmpty: {
                        message: '请填写用户登陆密码'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 10000,
                        message: '用户登陆密码不能少于6位'
                    },
                }
            },
        }
    },
    user_edit: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {},
    },
    group_add: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写用户组名称'
                    }
                }
            },
            discount: {
                validators: {
                    notEmpty: {
                        message: '请填写用户组默认折扣率'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^\d{1,3}$/,
                        message: '用户组默认折扣率格式错误'
                    }
                }
            },
            min: {
                validators: {
                    notEmpty: {
                        message: '请填写用户组最小积分'
                    }
                }
            },
            max: {
                validators: {
                    notEmpty: {
                        message: '请填写用户组最大积分'
                    }
                }
            },
        }
    },
    archive_category: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写分类名称'
                    }
                }
            },
            alias: {
                validators: {
                    notEmpty: {
                        message: '请填写分类别名'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^[a-z]{2,20}$/,
                        message: '分类别名以2-20位的小写字母组成'
                    }
                }
            },
        }
    },
    archive: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: '请填写内容标题'
                    }
                }
            },
        }
    },
    frag_place: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            site_id: {
                validators: {
                    notEmpty: {
                        message: '请选择所属网站'
                    },
                }
            },
            os: {
                validators: {
                    notEmpty: {
                        message: '请选择所属系统'
                    },
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写碎片位置名称'
                    }
                }
            },
        }
    },
    frag: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写碎片名称'
                    }
                }
            },
        }
    },
    task_employee: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            employee_name: {
                validators: {
                    notEmpty: {
                        message: '请选择一个员工'
                    }
                }
            },
        }
    },
    my_info: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            mobile: {
                validators: {
                    notEmpty: {
                        message: '请填写手机号'
                    },
                    stringLength: {
                        enabled: true,
                        min: 6,
                        max: 20,
                        message: '手机号不能大于20字符或小于6个字符'
                    },
                    regexp: {
                        enabled: true,
                        regexp: phone_regexp_str,
                        message: '请输入格式正确的手机号码'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: '请填写邮箱'
                    },
                    emailAddress: {
                        message: '员工邮箱格式错误'
                    }
                }
            },
        }
    },
    spec_add: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写规格名称'
                    }
                }
            },
            unit: {
                validators: {
                    notEmpty: {
                        message: '请填写计价单位'
                    }
                }
            },
        }
    },
    special: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写活动名称'
                    },
                }
            },
            alias: {
                validators: {
                    notEmpty: {
                        message: '请选择活动别名'
                    },
                    regexp: {
                        enabled: true,
                        regexp: /^[a-z0-9]{2,20}$/,
                        message: '别名以2-20位的小写字母或数字组成'
                    }
                }
            },
            'sites[]': {
                validators: {
                    notEmpty: {
                        message: '请至少选择一个可用网站'
                    },
                }
            },
        }
    },
    promotion: {
        message: '输入格式错误，请检查',
        feedbackIcons: feedbackIcons,
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '请填写促销活动名称'
                    },
                }
            },
            'sites[]': {
                validators: {
                    notEmpty: {
                        message: '请至少选择一个可用网站'
                    },
                }
            },
        }
    }
};