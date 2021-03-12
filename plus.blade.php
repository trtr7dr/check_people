<!DOCTYPE html>
<html>
    <head>
        <title>Входы и выходы</title><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Expires" content="-1">
    </head>
    <body>
        @include('layouts.warning')
        <link rel="stylesheet" href="{{asset('/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('/css/people.css')}}">
        <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">

        <script src="/js/jquery.min.js" type="text/javascript"></script>
        <h1 style="text-align: center; width: 100%; font-size: 300px;">Сегодня @php echo date('d.m.y'); @endphp</h1><br>
        <div class="row mains">


            @foreach($data as $el)
            <div class="col-md-1">

                <img  id="{{$el->id}}" src="/img/people/{{$el->img}}" 
                      @if($data->dop[$el->id]['done'] === 1) 
                      onclick='drop_people({{$el->id}}, "{{$el->name}}")' style="opacity:.5;"  
                      @else 
                      onclick='add_people({{$el->id}}, "{{$el->name}}")' 
                      @endif,  class="people">

                      <div id="p{{$el->id}}" class="plus" @if($data->dop[$el->id]['done'] === 1) style="display:block" @endif>+</div>
            </div>
            @endforeach
        </div>

        <h2 style="text-align: center; width: 100%; font-size: 100px;">Если ваш портрет серый и с плюсом, значит @php echo date('d.m'); @endphp вы на рабочем месте без температуры.</h2><br>
        <h2 style="text-align: center; width: 100%; font-size: 100px;">Не нашли себя? Обратитесь в 202 кабинет.</h2><br>


        <script>
            function add_people(id, name) {
                let data = name;
                $.ajax({
                    type: 'post',
                    url: '/people',
                    global: false,
                    data: {
                        'mode': 'add',
                        'name': data,
                        'id': parseInt(id),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    response: 'text',
                    success: function (res) {
                        $('#' + id).css('opacity', '.5');
                        $('#p' + id).css('display', 'block');
                        $('#' + id).attr('onclick', 'drop_people(' + id + ', "' + name + '")');
                    }
                });
            }

            function drop_people(id, name) {
                let data = name;
                $.ajax({
                    type: 'post',
                    url: '/people',
                    global: false,
                    data: {
                        'mode': 'drop',
                        'name': data,
                        'id': parseInt(id),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    response: 'text',
                    success: function (res) {
                        $('#' + id).css('opacity', '1');
                        $('#p' + id).css('display', 'none');
                        $('#' + id).attr('onclick', 'add_people(' + id + ', "' + name + '")');
                    }
                });
            }
        </script>

        <script>
            function rel_page() {
                document.location.reload();
            }
            let h = 1000 * 60 * 60;
            setInterval(function () {
                rel_page();
            }, h);
        </script>


        <script>
            function check_cart() {
                $.ajax({
                    type: 'post',
                    url: '/cart',
                    global: false,
                    data: {
                        'mode': 'check',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    response: 'text',
                    success: function (res) {
                        Object.values(res).forEach((element) => {
                            if (element !== null) {
                                console.log('add ' + element);
                                $('#' + element).click();
                            }
                        });
                    }
                });
            }

            var datet = new Date();
            var timeout = (datet.getHours() >= 7 && datet.getHours() < 10) ? 5000 : 1000 * 60 * 30; //проверяемся часто утром, редко в другое время.
            setInterval(function () {
                check_cart();
            }, timeout);

        </script>


    </body>
</html>
