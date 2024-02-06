// Page Navigation on Click Main Menu
$('.clspageparentmenu').on('click', function(e) {
    
    var pagename = $(this).attr('pagename');
    if(pagename)
    {
        e.preventDefault(); 
        if (e.ctrlKey){ //ctrl click event
            window.open(pagename,'_blank');
        }
        else{
            currentXhr.abort();
            render(pagename);
            window.history.pushState(pagename, 'Title', dirpath + pagename);
        }

    }
});