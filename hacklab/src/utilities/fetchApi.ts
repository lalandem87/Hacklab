const API_URL = "http://127.0.0.1:8000/api";

export async function fetchAPI(endpoint: string, method: string = "GET", data?: any, token? : string): Promise<any> {
    const header: Record<string, string> = {
        "Content-type": "application/json",
    }

    if(token) header["Authorization"] = `Bearer ${token}`;

    try {
        const request = await fetch(`${API_URL}/${endpoint}`, {
            method: method,
            headers: header,
            body: data ? JSON.stringify(data) : undefined

        });
        if(request.ok){
            return request.json();
        }
    } catch (e) {
        console.error(e);
    }
}