var taxonomyCategory = Class.create();
taxonomyCategory.prototype = {
    config: {},
    initialize: function (cfg) {
        this.config = cfg
    },

    fieldName: '',
    fieldStrings: {},
    toggleSelect: function(element, disable_value) {
        var thisClass = this;
        var input_disable = $(element).siblings().grep(new Selector('.el_disabled')).first();

        if (disable_value === undefined) {
            disable_value = $(input_disable).value == '1' ? 0 : 1;
        }
        $(input_disable).value = disable_value;

        var input_value = $(element).up('div.category_row').select('.input-text').first();
        if (disable_value) {
            $(input_value).addClassName('disabled');
        } else {
            if ($(input_value).hasClassName('disabled')) {
                $(input_value).removeClassName('disabled');
            }

            var carrot = $(element).siblings().grep(new Selector('.carrot')).first();
            if (carrot !== undefined && carrot.hasClassName('icon-carrot-closed')) {
                this.toggleShow(carrot, 'show');
            }
            var ul = $(element).up().siblings().grep(new Selector('.category_list')).first();
            if (ul !== undefined) {
                ul.select('.status').each(function(item) {
                    thisClass.toggleSelect(item, 0);
                });
            }
        }
        var newString = disable_value
            ? this.fieldStrings['row_disabled'] : this.fieldStrings['row_enabled'];
        $(element).update(newString);
    },
    toggleShow: function(element, status) {
        var ul = $(element).up().siblings().grep(new Selector('.category_list')).first();
        var showUl = false;
        if (element.hasClassName('icon-carrot-opened')) {
            element.removeClassName('icon-carrot-opened');
            element.addClassName('icon-carrot-closed');
        }else {
            showUl = true;
            element.removeClassName('icon-carrot-closed');
            element.addClassName('icon-carrot-opened');
        }

        if (ul !== undefined) {
            if (status !== undefined) {
                if (status == 'show') {
                    showUl = true;
                } else if (status == 'hide') {
                    showUl = false;
                }
            }
            if (showUl) {
                ul.show();
            } else {
                ul.hide();
            }
        }
    },
    showAll: null,
    toggleShowAll: function(parent) {
        var thisClass = this;
        $$('.category_taxonomy_category .carrot').each(function(element) {
            if (thisClass.showAll === null || thisClass.showAll === false) {
                thisClass.toggleShow(element, 'show');
            } else {
                thisClass.toggleShow(element, 'hide');
            }
        });
        if (thisClass.showAll === null || thisClass.showAll === false) {
            thisClass.showAll = true;
            parent.update(thisClass.fieldStrings['collapse_all']);
        } else {
            thisClass.showAll = false;
            parent.update(thisClass.fieldStrings['expand_all']);
        }
    },
    toggleSelectAll: function(parent) {
        var thisClass = this;
        $$('.category_taxonomy_category .status').each(function(element) {
            if (parent.hasClassName('enable')) {
                thisClass.toggleSelect(element, 0);
            } else {
                thisClass.toggleSelect(element, 1);
            }
        });
        if (parent.hasClassName('enable')) {
            parent.removeClassName('enable');
            parent.addClassName('disable');
            thisClass.showAll = true;
            $$('.category_taxonomy_category_all').first().update(thisClass.fieldStrings['collapse_all']);
            $(parent).update(thisClass.fieldStrings['disable_all']);
        } else {
            parent.removeClassName('disable');
            parent.addClassName('enable');
            $(parent).update(thisClass.fieldStrings['enable_all']);
        }
    },
    autoFillChildren: function(element) {
        var text = $(element).value;
        var ul = $(element).up('.category_row').siblings().grep(new Selector('.category_list')).first();
        if (ul !== undefined) {
            ul.select('.input-text').each(function(field) {
                if (!$(field).disabled && $(field).value == '') {
                    $(field).value = text;
                }
            });
        }
    }

};