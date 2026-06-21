import { useEffect, useState } from "react";
import { fetchAPI } from "./fetchApi";

export function useFetchWithToken(
  endpoint: string,
  method: string = "GET",
  token?: string,
) {
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    fetchAPI(endpoint, method, undefined, token)
      .then((r) => setData(r))
      .catch((e) => console.error(e))
      .finally(() => setLoading(false));
  }, [endpoint]);

  return { data, loading };
}
