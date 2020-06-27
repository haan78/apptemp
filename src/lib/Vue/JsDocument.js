export default (title) => {
    var head = document.createElement("head");
    var body = document.createElement("body");
    var container = document.createElement("div");
    var ch = document.createElement('meta');
    ch.httpEquiv = "Content-Type";
    ch.content = "text/html; charset=utf-8";
    document.title = (title ? title : "");
    head.appendChild(ch);
    body.appendChild(container);

    document.documentElement.appendChild(head);
    document.documentElement.appendChild(body);
    var loading = document.querySelector('[ data-role = __greeting__ ]');
    if ( loading ) loading.remove();
    return container;
}