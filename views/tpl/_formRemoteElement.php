<script id="formRemoteElement" type="text/template">
    <div class="remote-element">
        <form id="w1" action="/sftp/exec" method="post">
            <input type="hidden" name="type" value="<%= element.type %>">
            <input type="hidden" name="_csrf" value="<%= getToken() %>">
            <input type="hidden" name="RemoteElement[path]" value="<%= element.path %>">
            <div class="form-group field-remoteelement-name">
                <label class="control-label" for="remoteelement-name"><%=
                    element.type %> name</label>
                <input type="text" id="remoteelement-name" class="form-control"
                       name="RemoteElement[name]" required aria-required="true"
                       aria-invalid="true">
            </div>
            <div class="clearfix"></div>
            <div class="form-group field-remoteelement-rights">
                <label class="control-label" for="remoteelement-rights">Rights
                    <%= element.type %></label>
                <input required type="text" id="remoteelement-rights"
                       class="form-control" name="RemoteElement[rights]"
                       value="<%= element.rights %>" aria-required="true"
                       aria-invalid="false">
            </div>
            <div class="clearfix"></div>
            <button type="submit" class="btn btn-success">create</button>
        </form>
        <div class="clearfix"></div>
    </div>
</script>