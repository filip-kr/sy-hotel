// Tražilica gostiju na indexu
$('#pretragagost').keyup(function () {
    $.ajax({
        method: 'POST',
        url: $(location).attr("origin") + '/gosti/trazigosta/' + $(this).val(),
        success: function (rezultat) {
            rezultat = JSON.parse(rezultat);

            $('#rezultatigost').empty();

            for (var i = 0; i < rezultat.length; i++) {
                if (rezultat[i].drzava == 'Croatia') {
                    rezultat[i].dokument = rezultat[i].oib
                } else {
                    rezultat[i].dokument = rezultat[i].broj_putovnice
                }

                var red = $(
                    '<tr><td>' + rezultat[i].ime + '</td>' +
                    '<td>' + rezultat[i].prezime + '</td>' +
                    '<td>' + rezultat[i].email + '</td>' +
                    '<td>' + rezultat[i].datum_rodenja + '</td>' +
                    '<td>' + rezultat[i].drzava + '</td>' +
                    '<td>' + rezultat[i].dokument + '</td>' +

                    '<td>' +
                    '<div class=\"btn-group dropstart d-flex justify-content-center\">\
                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" data-bs-toggle=\"dropdown\">\. . .</button>\
                            <ul class=\"dropdown-menu">\
                                <li><a class="dropdown-item" href="' + $(location).attr('origin') + '/gosti/izmjenaunosa/' + rezultat[i].sifra + '"' + '>Uredi</a></li>\
                                <li><a class="dropdown-item" href="' + $(location).attr('origin') + '/gosti/brisanjeunosa/' + rezultat[i].sifra + '"' + '>Obriši</a>\
                            </ul>\
                    </div>'
                    + '</td></tr>'
                );

                $('#gostipaginacijaindex').empty();
                $('#rezultatigost').append(red);
            }
        }
    })
});