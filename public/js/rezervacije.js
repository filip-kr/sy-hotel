// Tražilica gostiju kod novog unosa
$('#pretragarezervacija').keyup(function () {
    $.ajax({
        method: 'POST',
        url: $(location).attr('origin') + '/rezervacije/trazigosta/' + $(this).val(),
        success: function (rezultat) {
            rezultat = JSON.parse(rezultat);

            $('#rezultatrezervacija').empty();

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
                    '<td>' + '<a href="' + $(location).attr("origin") + '/rezervacije/noviunos/' + rezultat[i].sifra + '"' +
                    'class="btn btn-success d-flex justify-content-center">Odaberi</a>' + '</td></tr>'
                );

                $('#gostipaginrezervacija').empty();
                $('#rezultatrezervacija').append(red);
            }
        }
    })
});