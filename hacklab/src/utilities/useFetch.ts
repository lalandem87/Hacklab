import { fetchAPI } from "./Fetchapi";
import { useState, useEffect } from "react";

export function useFetch(endpoint: string){
    const [data, setData] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
         fetchAPI(endpoint)
         .then(res => setData(res))
         .finally(() => setLoading(false))
    }, [endpoint]);
    
    return {data, loading};
}