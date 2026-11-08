document.addEventListener("DOMContentLoaded", function () {
    /
    const countryInput = document.getElementById("country");
    const lookupButton = document.getElementById("lookup");
    const citiesButton = document.getElementById("lookup-cities");
    const resultDiv = document.getElementById("result");

    
    function searchWorld(lookupType) {

        
        const country = countryInput.value.trim();

        
        const params = new URLSearchParams();

        params.append("country", country);
        params.append("lookup", lookupType);

        const url = "world.php?" + params.toString();

        
        fetch(url)
            .then(function (response) {

                if (!response.ok) {
                    throw new Error("Unable to retrieve results.");
                }

                return response.text();

            })

            .then(function (data) {

                
                resultDiv.innerHTML = data;

            })

            .catch(function (error) {

                resultDiv.textContent = "Error: " + error.message;

            });

    }


    // Exercise 3 and 4: Country Lookup button
    lookupButton.addEventListener("click", function () {

        searchWorld("countries");

    });


    // Exercise 5: Lookup Cities button
    citiesButton.addEventListener("click", function () {

        searchWorld("cities");

    });

});
