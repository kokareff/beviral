{extends file="Layout/Admin.tpl"}

{block name="title"}Таски{/block}

{block name="header"}Таски <small>обзор</small>{/block}

{block name="breadcrumb"}
    <li><a href="/admin/"><i class="icon-dashboard"></i> Дашбоард</a></li>
    <li><a href="/admin/task"><i class="fa fa-edit"></i> Фоновые задачи</a></li>
    <li class="active"><i class="fa fa-plus"></i> Лог задачи</li>
{/block}

{block name="content"}
    <div class="col-lg-12">
        <h1>Лог задачи</h1>
        <table class="table">
        {foreach $logs as $logData}
            <tr>
                <td>{date('d.m.y H:i:s',$logData.time)}</td>
                <td>{$logData.message}</td>
            </tr>
        {/foreach}
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