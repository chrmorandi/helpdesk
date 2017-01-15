String.prototype.replaceAll = function (search, replace) {
    return this.split(search).join(replace);
};

function parseJson(json) {
    if (json != null && json != '') {
        try {
            return $.parseJSON(json);
        } catch (e) {
            return false;
        }
    }
}

function getExtensionFile(filename) {

    return filename.split('.').pop() || 'none';
}


function getEmailFrom(from) {
    if (from.indexOf('<') != -1)
        return from.split('<')[1].replace('>', '');
    else return from || '';
}

function reduceCount(elem) {
    var count = $(elem);
    if (+count.html() > 0)
        count.html(+count.html() - 1)
}

function wrapText(text) {
    return "<pre>" + text + "</pre>";
}

function getToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function convertUnixDate(timestamp) {
    var theDate = new Date(timestamp * 1000);
    var formatDate;
    var options = {
        month: 'long',
        day: 'numeric',
        weekday: 'long',
        timezone: 'UTC',
        hour: 'numeric',
        minute: 'numeric'
    };
    var stringDate = theDate.toLocaleString("ru", options);
    formatDate = stringDate.charAt(0).toUpperCase() + stringDate.substr(1).toLowerCase();

    return formatDate;
}

function geType(type) {
    if (type == 1) return 'file';
    else return "folder";

}

function find(array, value) {

    for (var i = 0; i < array.length; i++) {
        if (array[i] == value) return true;
    }

    return false;
}