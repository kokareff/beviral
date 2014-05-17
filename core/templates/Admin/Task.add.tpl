{extends file="Layout/Admin.tpl"}

{block name="title"}Таски{/block}

{block name="header"}Таски <small>обзор</small>{/block}

{block name="breadcrumb"}
    <li><a href="/admin/"><i class="icon-dashboard"></i> Дашбоард</a></li>
    <li><a href="/admin/task"><i class="fa fa-edit"></i> Фоновые задачи</a></li>
    <li class="active"><i class="fa fa-plus"></i> Добавление задачи</li>
{/block}

{block name="content"}
    <div class="col-lg-5">
        <h4>Добавить задачу</h4>

        <form action="/admin/task/write" method="POST" role="form">
                <div class="form-group">
                <label for="taskSelect">Название</label>
                <select name="taskName" id="taskSelect" class="form-control">
                    {foreach $allowedTasks as $task}
                        <option value="{$task}">{$task}</option>
                    {/foreach}
                </select>
                </div>
                <div id="taskParams">

                </div>
                <p>
                    <button class="btn btn-success ajax" type="submit"><i class="icon-plus"></i> Записать</button>
                </p>

        </form>
        <script type="text/javascript">
            var currErrorCallback = function (errors) {
                showErrors(errors);
            };

            var currSuccessCallback = function (data) {
                redirect();
            }
        </script>
    </div>
    <div class="col-lg-5">
    <h4>Ожидающие выполнения</h4>
    <table class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>Название</td>
            <td>Параметры</td>
        </tr></thead>
        <tbody>
        {foreach $waitTasks as $task}
            <tr>
                <td>{$task._id}</td>
                <td>{$task.name}</td>
                <td>{json_encode($task.params)}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    </div>
{/block}
{block name="scripts"}
   <script>
      function refreshParams(){
         $('#taskParams').html('<img src="/img/loader.gif" alt="Загружаем..."/>');
         $.post('/admin/task/params',
               {
                  taskName:$('#taskSelect').val()
               }, function(data){
                  $('#taskParams').html(generateFormFields(data['data']));
               })
      }
      refreshParams();
      $('#taskSelect').change(function(e){
         refreshParams();
      })
   </script>
{/block}