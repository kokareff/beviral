{extends file="Layout/Site.tpl"}

{block name="title"}Юзер{/block}

{block name="header"}Регистрируйся <small>во имя печенек.</small>{/block}

{block name="breadcrumb"}
    <li><a href="/user"><i class="icon-dashboard"></i> Дашбоард</a></li>
{/block}

{block name="content"}
    <div class="col-lg-6">

        <h3>Регистрация нового {if ($acl=='wm')}вебмастера{elseif ($acl=='rekl')}рекламодателя{/if}</h3>

        <small >Обязательны <span style="color: red">ВСЕ</span> поля</small>

        <form role="form" action="/site/user/check-register" method="POST" style="padding-top: 1em;">
            <div class="form-group">

                <label>Nick: </label>
                <input class="form-control" type="text" name="nick"/><br/>

                <label>Пароль: </label>
                <input class="form-control" type="password" name="pass"/><br/>

                <label>Подтверждение пароля: </label>
                <input class="form-control" type="password" name="pass_confirm"/><br/>

                <input name="acl" type="hidden" value="{$acl}"/>

                <button type="submit" class="btn btn-default ajax">Зарегистрироваться</button>

            </div>
        </form>
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