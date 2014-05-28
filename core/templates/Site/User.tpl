{extends file="Layout/Site.tpl"}

{block name="title"}Юзер{/block}

{block name="header"}Добро пожаловать <small>на темную сторону силы</small>{/block}

{block name="content"}
    <div class="col-lg-6">
        <form role="form" action="/site/user/check-login" method="POST">
            <div class="form-group">

                <label>Nick: </label>
                <input class="form-control" type="text" name="nick"/><br/>

                <label>Пароль: </label>
                <input class="form-control" type="password" name="pass"/><br/>

                <button type="submit" class="btn btn-default ajax">Войти</button>


            </div>
        </form>
        <h3>Зарегистрироваться как: </h3>
        <a href="/site/register/wm">Вебмастер</a><br/>
        <a href="/site/register/rekl">Рекламодатель</a>
    </div>
    <script type="text/javascript">
        var currErrorCallback = function (errors) {
            showErrors(errors);
        };

        var currSuccessCallback = function (data) {
            if(data.location){
                redirect(data.location)
            } else {
                redirect(document.referrer);
            }
        }
    </script>
{/block}