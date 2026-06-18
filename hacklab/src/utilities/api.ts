const API_URL = "http://127.0.0.1:8000/api";

export async function fetchAPI(endpoint: string): Promise<any> {
    try {
        const request = await fetch(`${API_URL}/${endpoint}`);
        if(request.ok){
            return request.json();
        }
    } catch (e) {
        console.error(e);
    }
}