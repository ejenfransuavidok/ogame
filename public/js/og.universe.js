'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Universe = exports.Universe = function Universe() {
    _classCallCheck(this, Universe);

    if ($('#universe-start-generation').length) {
        this.timer = setInterval(function () {
            $.ajax({
                type: 'post',
                url: '/logger',
                data: '',
                dataType: "json",
                success: function success(response) {
                    console.log(response.messages);
                    $('#console').html(response.messages);
                },
                error: function error(data) {
                    console.error(data);
                }
            });
        }, 1500);
        $(document).on('click', '#universe-start-generation', function (evt) {
            evt.preventDefault();
            $.ajax({
                type: 'post',
                url: '/creator',
                data: '',
                dataType: "json",
                success: function success(response) {
                    console.log(response.messages);
                },
                error: function error(data) {
                    console.error(data);
                }
            });
        });
    }
};