<script id="browserList" type="text/template">

    <table id="tableBrowse" class="table table-hover">
        <thead>
        <tr>
            <th>Имя</th>
            <th>Размер</th>
            <th>Тип</th>
            <th>uid</th>
            <th>Права</th>
            <th>Создан</th>
            <th>Изменён</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="move" data-target="../">
                <i style="margin-right: 5px" class="fa fa-folder-o" aria-hidden="true"></i>
                <a>../</a>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <%_.each(list,function(elem,i,l){

        var type = geType(elem.type);
        %>
        <tr>
            <td class="<%= (type == 'folder') ? 'move' : 'getFile' %>" data-target="<%= elem.filename %>/">
                <i style="margin-right: 5px" class="fa fa-<%= type %>-o" aria-hidden="true"></i>
                <a><%= elem.filename %></a>
            </td>
            <td><%= (elem.size == '4096') ? '-' : elem.size %></td>
            <td><%= (type == 'file') ? 'файл (.'+ getExtensionFile(elem.filename)+')' : 'папка'%></td>
            <td><%= elem.uid %></td>
            <td><%= elem.permissions %></td>
            <td><%= convertUnixDate(elem.atime) %></td>
            <td><%= convertUnixDate(elem.mtime) %></td>
        </tr>
        <% }) %>
        </tbody>
    </table>
</script>