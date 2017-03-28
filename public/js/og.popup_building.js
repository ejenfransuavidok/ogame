'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var PopupBuilding = exports.PopupBuilding = function () {
    function PopupBuilding() {
        _classCallCheck(this, PopupBuilding);

        if ($('.popup_building').length) {
            this.exec();
        }
    }

    _createClass(PopupBuilding, [{
        key: 'exec',
        value: function exec() {
            var _this = this;

            $(document).on('click', '[data-entity=srcbuilding]', function (evt) {
                return _this.src_building_select(evt);
            });
            $(document).on('click', '[data-entity=popup_building-popup__pp-popup__pp-control-build-button]', function (evt) {
                return _this.build(evt);
            });
        }
    }, {
        key: 'build',
        value: function build(evt) {
            var obj = $(evt.target);
            this.close_popup(obj);
        }
    }, {
        key: 'close_popup',
        value: function close_popup(obj) {
            $(obj).closest('.popup__pp').removeClass('is-open');
            $.notify($(obj).closest('.popup__pp').data('building_id'));
        }
    }, {
        key: 'src_building_select',
        value: function src_building_select(evt) {
            var obj = $(evt.target).closest('.build__item');
            var id = $(obj).data('identifier');
            $('[data-entity=popup_building-popup__pp]').data('building_id', id);
            var title = $(obj).find('[data-entity=keep__item-text]').first().html();
            var description = $(obj).find('[data-entity=building-description]').first().html();
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-content-title]').html(title + '<br><span class="btn btn_lvl">lv.1</span>');
            $('[data-entity=popup_building-popup__pp-layout-popup__pp-content-description]').html(description);
        }
    }]);

    return PopupBuilding;
}();