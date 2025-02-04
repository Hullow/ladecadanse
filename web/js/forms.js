$('input.datepicker').Zebra_DatePicker({
    direction: true,
    format: 'd.m.Y',
    zero_pad: true,
    days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    months: ['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre'],
    show_clear_date: true,
    lang_clear_date: "Effacer",
    show_select_today: "Aujourd’hui"
});

$('input.datepicker_from').Zebra_DatePicker({
    direction: true,
    pair: $('input.datepicker_to'),
    format: 'd.m.Y',
    zero_pad: true,
    days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    months: ['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre'],
    show_clear_date: true,
    lang_clear_date: "Effacer",
    show_select_today: "Aujourd’hui",
    readonly_element: false
});

$('input.datepicker_to').Zebra_DatePicker({
    direction: 1,
    format: 'd.m.Y',
    zero_pad: true,
    days: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    months: ['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre'],
    show_clear_date: true,
    lang_clear_date: "Effacer",
    show_select_today: "Aujourd’hui",
    readonly_element: false
});

$(".chosen-select").chosen({
    allow_single_deselect: true,
    no_results_text: "Aucun &eacute;l&eacute;ment correspondant n'a &eacute;t&eacute; trouv&eacute;",
    include_group_label_in_selected: true,
    search_contains: true
});


$('.shiftcheckbox').shiftcheckbox({

    // Options accept selectors, jQuery objects, or DOM
    // elements.

    checkboxSelector: ':checkbox',
    selectAll: $('#demo1 .all'),
    ignoreClick: 'a'

});

$('.file-upload-size-max').bind('change', function ()
{

    if (this.files[0].size > 2097152)
    {
        alert("La taille du fichier que vous avez sélectionné dépasse la limite autorisée (2 Mo), merci d'en choisir un plus léger");
    }
});

tinymce.init({
    selector: 'textarea.tinymce',
    height: 500,
    menubar: false,
    plugins: [
        'autolink lists link charmap',
        'searchreplace visualblocks code',
        'paste code help wordcount'
    ],
    toolbar: 'bold italic link | h4 bullist numlist blockquote | undo redo | visualblocks removeformat code',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tiny.cloud/css/codepen.min.css'
    ]
});