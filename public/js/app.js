$(document).ready(function () {

    let $city = $('#city_name');
// When sport gets selected ...
    $city.on('change', function() {
        // ... retrieve the corresponding form.
        let $form = $(this).closest('form');
        // Simulate form data, but only include the selected sport value.
        let data = {};
        data[$city.attr('name')] = ($city.children()[$city.val()]).textContent;
        //data = 'name=' + ($city.children()[$city.val()]).textContent;

        // Submit data via AJAX to the form's action path.
        console.log(data);

        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                console.log("SUCCESS!");
                // Replace current position field ...
                $('#city_places').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#city_places')
                );
                // Position field now displays the appropriate positions.
            }
        });
    });
})

