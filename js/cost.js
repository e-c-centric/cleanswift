/**
 * Fetches the latitude and longitude of a given GhanaPost GPS address.
 * @param {string} address - The GhanaPost GPS address (e.g., "AK-484-9321").
 * @returns {Promise<{ latitude: number, longitude: number }>} - The coordinates of the address.
 */
async function findCoordinates(address) {
    const myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

    const urlencoded = new URLSearchParams();
    urlencoded.append("address", address);

    const requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: urlencoded,
        redirect: 'follow'
    };

    try {
        const response = await fetch("https://ghanapostgps.sperixlabs.org/get-location", requestOptions);
        const result = await response.json();

        if (result.found && result.data.Table.length > 0) {
            const location = result.data.Table[0];
            return {
                latitude: location.CenterLatitude,
                longitude: location.CenterLongitude
            };
        } else {
            throw new Error('Address not found');
        }
    } catch (error) {
        console.error('Error fetching coordinates:', error);
        throw error;
    }
}

/**
 * Calculates the distance between two geographical coordinates using the Haversine formula.
 * @param {{ latitude: number, longitude: number }} coord1 - The first set of coordinates.
 * @param {{ latitude: number, longitude: number }} coord2 - The second set of coordinates.
 * @returns {number} - The distance in kilometers.
 */
function calculateHaversineDistance(coord1, coord2) {
    const toRadians = (degree) => degree * (Math.PI / 180);

    const R = 6371; // Earth's radius in kilometers
    const dLat = toRadians(coord2.latitude - coord1.latitude);
    const dLon = toRadians(coord2.longitude - coord1.longitude);

    const lat1 = toRadians(coord1.latitude);
    const lat2 = toRadians(coord2.latitude);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
}

/**
 * Computes the shortest distance between two GhanaPost GPS addresses and the current location.
 * @param {string} address1 - The first GhanaPost GPS address.
 * @param {string} address2 - The second GhanaPost GPS address.
 * @returns {Promise<number>} - The shortest distance in kilometers.
 */
async function computeDistance(address1, address2) {
    try {
        const [coord1, coord2] = await Promise.all([
            findCoordinates(address1),
            findCoordinates(address2)
        ]);

        const getCurrentPosition = () => {
            return new Promise((resolve, reject) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(resolve, reject);
                } else {
                    reject(new Error('Geolocation not supported'));
                }
            });
        };

        const position = await getCurrentPosition();
        const currentCoord = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
        };

        const distanceCurrentTo1 = calculateHaversineDistance(currentCoord, coord1);
        const distanceCurrentTo2 = calculateHaversineDistance(currentCoord, coord2);
        const distance1To2 = calculateHaversineDistance(coord1, coord2);

        const distances = [distanceCurrentTo1, distanceCurrentTo2, distance1To2];
        const shortestDistance = Math.min(...distances);

        return shortestDistance;
    } catch (error) {
        console.error('Error computing distance:', error);
        throw error;
    }
}

/**
 * Calculates the cost of a delivery based on the shortest distance.
 * @param {string} address1 - The first GhanaPost GPS address.
 * @param {string} address2 - The second GhanaPost GPS address.
 * @returns {Promise<number>} - The total cost in the applicable currency.
 */
async function computeCost(address1, address2) {
    try {
        const distance = await computeDistance(address1, address2);
        const ratePerKm = 10;
        const totalCost = 1.25 * distance * ratePerKm;
        return totalCost;
    } catch (error) {
        console.error('Error computing cost:', error);
        throw error;
    }
}