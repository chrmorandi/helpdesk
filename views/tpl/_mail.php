<script type="text/template" id="templateMail">
    <div style="border: none" class="box box-solid box-info">
        <div style="border-radius: 0" class="box-header">
            <div class="close-mass">
                <a href="#"><i class="fa fa-close" aria-hidden="true"></i></a>
            </div>
            <h3 class="box-title">
                Information about the letter #
                <div style="display: inherit" class="clear uid"><%= mail.uid
                    %>
                </div>
            </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="pull-right">
                <button class="reply-icon btn btn-success">Reply</button>
                <button class="btn btn-danger deleteAll" id="<%= mail.uid%>">Delete</button>
            </div>
            <i class="fa fa-calendar" aria-hidden="true"> <%=
                convertUnixDate(mail.udate) %> || </i>
            <i class="fa fa-user" aria-hidden="true"> <%= mail.from %></i>
            <br>
            <i class="fa fa-ticket" aria-hidden="true"> <%= mail.subject %></i>
        </div><!-- /.box-body -->
        <div class="parent-reply">
            <% if(mail.hasOwnProperty('parentReplys')){%>
            <%_.each(mail.parentReplys,function(parent,i,l){%>
            <div class="reply" id="<%=parent.id%>">
                <div class="reply-from">from :<%= parent.from %> --> <%=convertUnixDate(parent.udate)%></div>
                <div class="reply-subject">subject :<%=parent.subject%></div>
            </div>

            <% }) %>
            <% } %>
        </div>
        <div class="info-mass">
            <div id="preload"></div>
            <iframe width="100%" style="min-height: 300px" class="textHtml clear ">

            </iframe>
            <div class="clearfix"></div>
        </div>
        <div class="cleafix"></div>
        <div class="child-reply">

            <% if(mail.hasOwnProperty('childReplys')){%>
            <%_.each(mail.childReplys,function(child,i,l){%>
            <div class="reply" id="<%=child.id%>">
                <div class="reply-from">from :<%= child.from %> --> <%=convertUnixDate(child.udate)%></div>
                <div class="reply-subject">subject :<%=child.subject%></div>
            </div>
            <% }) %>
            <% } %>
        </div>
    </div>
    <div class="clearfix"></div>
    <% if(mail.attachment_mail.length > 0){%>
    <div id="view-attach">
        <i class="fa fa-sort" aria-hidden="true"></i> View <span
            class="c-attach"><%= mail.attachment_mail.length %></span> attachment
        files
    </div>
    <div style="display:none;overflow: hidden" class="attachment-block">
        <ul style="margin: 0;padding: 0">
            <%_.each(mail.attachment_mail,function(attach,i,l){%>

            <li class="li-att">
                <a class="att-item" download
                   href="attach/<%= attach.fileName %>">
                    <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                    Attachment <%= attach.fileName %>
                </a>
                <div class="clearfix"></div>
                <br>
                <button style="float: left;margin-right: 20px" type="submit"
                        id="<%= attach.mail_uid %>"
                        class="delete btn btn-danger">Delete attachment
                </button>
                <p><i class="fa fa-info" aria-hidden="true"></i>
                    file extension : <%=getExtensionFile(attach.fileName) %>
                </p>
            </li>
            <div class="clearfix"></div>

            <% }) %>
        </ul>
        <div class="clearfix"></div>
    </div>
    <% } %>
    <form enctype="multipart/form-data" method="post" action="/mail/send" id="replyForm">
        <div class="text-blocks">
            <label for="to">To</label>
            <input class="form-control" id="to" name="to"
                   value="<%= getEmailFrom(mail.from) %>" type="text">
            <label for="subject">Subject</label>
            <input class="form-control" id="subject" name="subject" type="text">
            <input class="token" type="hidden" name="_csrf">
            <label for="text">Text</label>
            <textarea placeholder="Reply..." class="form-control" name="text"
                      id="emailText" cols="30"
                      rows="10"></textarea>
            <br>
            <button type="submit" class="btn btn-success"><i class="fa fa-send" aria-hidden="true"></i> Send</button>
            <div class="attach-block"></div>
            <div class="clearfix"></div>
        </div>
        <div id="drop" class="dropzone"></div>
        <div class="clearfix"></div>
    </form>
</script>