<script type="text/template" id="previewMail">
    <%

    _.each(mails,function(mail,i,l){

    %>
    <li class="mass" title="<%= mail.subject %>"><!-- start message -->
        <a class="get-mail" data-target="<%= mail.uid %>" href="#mail/<%= mail.uid %>">
            <div class="pull-left">
                <img src="/images/gmail.png" class="img-circle"/>
            </div>
            <h4 style="float: none">
                <%= mail.from %>
            </h4>
            <small><i class="fa fa-clock-o"></i> <%= convertUnixDate(mail.udate)
                %>
            </small>
            <p><%= mail.subject %></p>
        </a>
        <div title="Скрыть"
             class="hide-preview">
            <i style="width: 20px"
               class="fa fa-close"
               aria-hidden="true"></i>
        </div>
        <div title="Отметить как важное" class="important">

        </div>
    </li>
    <% }) %>
</script>