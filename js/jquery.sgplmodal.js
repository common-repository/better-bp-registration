(function($) {
    $(document).ready(function($) {
        $.sgplmodal = {

            selector: '',
            maxWidth : 330,
            beforeOpenSubscriber : [],
            afterOpenSubscriber : [],
            beforeCloseSubscriber : [],
            contentSelector: '',
            content: '',

            beforeOpenTriger : function() {
                this.beforeOpenSubscriber.map(function(func) {
                    return func();
                });
            },

            afterOpenTriger : function() {
                this.afterOpenSubscriber.map(function(func) {
                    return func();
                });
            },

            beforeCloseTriger : function() {
                this.beforeCloseSubscriber.map(function(func) {
                    return func();
                });
            },

            beforeOpen : function(func) {
                this.beforeOpenSubscriber.push(func);
            },

            afterOpen : function(func) {
                this.afterOpenSubscriber.push(func);
            },

            beforeClose : function(func) {
                this.beforeCloseSubscriber.push(func);
            },

            getTopIndent : function() {
                var topIndent = $(document).scrollTop();
                topIndent = (topIndent / 2) + topIndent;

                if (topIndent == 0) {
                    topIndent = $(window).height() / 4;
                }
                return topIndent;
            },

            open : function(selector) {
                this.selector = selector;

                this.beforeOpenTriger();

                var topIndent = this.getTopIndent();

                this.content = $(selector).html();
                $(selector).empty();

                $('body').append('<div class="overlay"></div>');
                $('body').append('<div id="modal-content-wrapper" style="top: ' + topIndent + 'px; max-width: ' + this.maxWidth + 'px;"><div id="modal-content"><div class="modal-close">X</div></div></div>');

                $('body').css('overflow-x', 'hidden');
                $('#modal-content').append(this.content);

                this.afterOpenTriger();
            },

            close : function() {
                this.beforeCloseTriger();

                $('.overlay').remove();
                $('#modal-content-wrapper').remove();
                $('body').css('overflow-x', 'none');
               
                this.beforeOpenSubscriber = [];
                this.afterOpenSubscriber = [];
                this.beforeCloseSubscriber = [];

                $(this.selector).html(this.content);
            },

            bindClose : $('body').on('click', 'div.modal-close', function(e) {
                e.preventDefault();
                $.sgplmodal.close();
            }),
        }
    });
})(jQuery);
