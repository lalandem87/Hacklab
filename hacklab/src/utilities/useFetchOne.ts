import { useEffect, useState } from "react";
import { fetchAPI } from "./fetchApi";


export function useFetchOne(endpoint: string){
    const [data, setData] = useState<any>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchAPI(endpoint)
        .then((r) => setData(r))
        .finally(() => setLoading(false))
    }, [endpoint] )
    
    return {data, loading};
}