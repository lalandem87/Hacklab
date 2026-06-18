const API_URL = "http://127.0.0.1:8000/api";

export async function fetchAPI(endpoint: string, method: string = "GET", data?: any): Promise<any> {
    try {
        const request = await fetch(`${API_URL}/${endpoint}`, {
            method: method,
            headers: {
                "Content-type": "application/json"
            },
            body: data ? JSON.stringify(data) : undefined

        });
        if(request.ok){
            return request.json();
        }
    } catch (e) {
        console.error(e);
    }
}