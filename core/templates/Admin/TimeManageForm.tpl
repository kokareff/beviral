<form>

    <div class="well">
        <strong>С</strong>

        <div id="fromDate" class="input-append date" style="display: inline">
            <input data-format="dd.MM.yyyy hh:00:00" type="text"
                   value="{$smarty.get.fromDate|default:date('d.m.Y H:00:00',strtotime('1 day ago'))}"
                   name="fromDate"/>
            <a class="add-on btn btn-default">
                Дата
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <strong>По</strong>

        <div id="toDate" class="input-append date" style="display: inline">
            <input data-format="dd.MM.yyyy hh:00:00" type="text"
                   value="{$smarty.get.toDate|default:date('d.m.Y H:00:00')}"
                   name="toDate"/>
            <a class="add-on btn btn-default">
                Дата
            </a>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" value="Пересчитать" class="btn"/>
    </div>
</form>