$(document).ready(function () {
    $(".ajax").click(function (e) {
        e.preventDefault();

        if (currErrorCallback && currSuccessCallback) {
            sendForm($(this).closest("form"), currErrorCallback, currSuccessCallback);
        }
    })

    $(".remove").click(function (e) {
        e.preventDefault();

        text = '';
        if (this.getAttribute('rel')) {
            text = "Вы действительно хотите удалить " + this.getAttribute("rel")
        } else {
            text = "Удалить?"
        }

        _confirm({
            url: this.getAttribute("href"),
            data: {},
            title: 'Подтвердите удаление',
            text: text,
            ok: 'Удалить',
            cancel: 'Отмена'
        }).done(function(data) {
               /* if(true)
                    console.log(data);
                else*/
                    document.location.reload();


            }).fail(function() {

            });
    });


    $(".send_act").click(function(e){
        e.preventDefault();
        $.post(this.getAttribute("href"), function(){
            document.location.reload();
        })
    })
});


