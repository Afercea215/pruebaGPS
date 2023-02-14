$(document).ready( function () {
    let datos = [];
    $.ajax({
        async:false,
        method:'GET',
        contentType: 'application/json',
        url:'http://localhost:8000/api/mensaje',
        
    }).done(function (data) {
        $.each(data.mensajes, function (key,val) {
            if (val.valido) {
                val.valido = '<img src="/images/tick.png" data-valido=true id="mensaje_img_'+val.id+'">'
            }else{
                val.valido = '<img src="/images/x.png" data-valido=false id="mensaje_img_'+val.id+'">'
            }
            datos.push(val);
        })

        $('#tablaValidar').DataTable({
            columns: [
                { data: 'fecha' },
                { data: 'banda' },
                { data: 'modo' },
                { data: 'emisor' },
                { data: 'valido' },
            ],
            data: /* JSON.parse */(datos),
        });

        $('[id^=mensaje_img_')
            .click(function () {
                if (!$(this).data('valido')) {
                    //let datos = $(this).data('obj');
                    let id = $(this).attr('id').split('_')[2];
                    let obj={};
                    //datos.valido = true;
                    $.ajax({
                        async:false,
                        type:'GET',
                        contentType: 'application/json',
                        url:'http://localhost:8000/api/mensaje/'+id,
                    }).done(function (data) {
                        obj = data.mensaje;
                    })

                    obj.valido = true;
                    console.log(obj);
                    
                    $.ajax({
                        async:false,
                        type:'PUT',
                        contentType: 'application/json',
                        url:'http://localhost:8000/api/mensaje/'+id,
                        data: JSON.stringify(obj),
                    }).done(function (data) {
                        debugger
                    })
                    $(this).attr('src', '/images/tick.png')

                }else{
                    alert('Mensaje ya validado')

                }
            })
    })
    console.log(datos);

   
} );