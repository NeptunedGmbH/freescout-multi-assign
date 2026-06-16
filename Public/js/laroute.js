(function () {
    var module_routes = [
    {
        "uri": "multi-assign\/toggle",
        "name": "multiassign.toggle"
    },
    {
        "uri": "multi-assign\/clear",
        "name": "multiassign.clear"
    }
];

    if (typeof(laroute) != "undefined") {
        laroute.add_routes(module_routes);
    } else {
        console.log('laroute not initialized, can not add module routes:');
        console.log(module_routes);
    }
})();
