var btn_get_trips = document.getElementById('get_trips');

function get_trips()
{
    $.ajax({
        url: 'http://localhost/Travelapp/api/trips',
        type: 'GET',
        success: function(data)
        {
            console.log(data);
            var jsonData = JSON.parse(JSON.stringify(data));
            var container = document.getElementById('container');

            container.innerHTML = '';
            for(var i=0; i<jsonData.length; i++)
            {
                var p = document.createElement("p");
                var content = jsonData[i].id + ", " + jsonData[i].country + ", "
                + jsonData[i].region + ", " + jsonData[i].departure_date + ", "
                + jsonData[i].return_date;
                container.append(content, p);
            }
        }
    });
}

btn_get_trips.addEventListener("click", function()
{
    get_trips();
});
