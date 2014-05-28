{extends file="Layout/Admin.tpl"}

{block name="title"}Таски{/block}

{block name="header"}Таски <small>обзор</small>{/block}

{block name="breadcrumb"}
    <li><a href="/admin"><i class="icon-dashboard"></i> Дашбоард</a></li>
    <li class="active"><i class="fa fa-edit"></i> Фоновые задачи</li>
{/block}

{block name="content"}
    <a class="btn btn-success" href="/admin/task/add"><i class="fa fa-plus"></i> Добавить задачу</a>
    <a class="btn btn-warning send_act" href="/admin/task/update"><i class="fa fa-refresh">
    </i> Обновить планировщик</a>
    {if count($tasks)>0}
    <table class="table" style="margin-top: 20px">
        <thead>
        <tr>
            <td>Id</td>
            <td>Название</td>
            <td>Статус</td>
            <td>Прогресс</td>
            <td>Действия</td>
        </tr>
        </thead>
        <tbody>
        {foreach $tasks as $task}
            <tr>
                <td>{$task._id}</td>
                <td>{$task.name}<br>
                    Параметры: {json_encode($task.params)}</td>
                <td>
                    {if $task.status==1}
                        <span style="color: darkslategray">Ожидание</span>
                    {elseif $task.status==2}
                        <span style="color: darkorchid">В процессе</span>
                    {elseif $task.status==3}
                        <span style="color: green">Закончена</span>
                    {elseif $task.status==4}
                        <span style="color: indianred">Прервана</span>
                    {elseif $task.status==5}
                        <span style="color: red">Прервана с ошибками</span>
                        <br/>
                        {json_encode($task.errors)}
                    {/if}
                </td>
                <td>{$task.progress}</td>
                <td>
                    <a class="btn btn-warning" href="/admin/task/log?taskId={$task._id}"><i class="fa fa-check"></i> Логи</a>
                    <a class="btn btn-danger send_act" href="/admin/task/del?taskId={$task._id}"><i class="fa fa-ban"></i> Удалить</a>
                </td>
            </tr>
        {/foreach}
        </tbody>

    </table>
    {/if}
    <script type="text/javascript">
        var currErrorCallback = function (errors) {
            showErrors(errors);
        };

        var currSuccessCallback = function (data) {
            redirect();
        }
    </script>
{/block}