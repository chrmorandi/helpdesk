<script id="consoleTpl" type="text/template">
    <div id="console" class="tplConsole">
        <div class="con-block" style="width: 100%" id="resp">
            <pre><%= model.response %></pre>
        </div>
        <div id="userline" style="color: #50d050;" class="con-block">
            <%=model.user %>
        </div>
        <div class="con-block" id="currentDirName">&#160;<%= model.dir %></div>
        <input type="text" class="con-block" id="command"><%= model.command%></input>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</script>