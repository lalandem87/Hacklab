import type { JSX } from "react/jsx-runtime";
import "./LeaderBoard.scss";
import { useFetch } from "../../utilities/useFetch";
import { useFetchWithToken } from "../../utilities/useFetchWithToken";

export function Leaderboard(): JSX.Element {
  const { data: users, loading } = useFetch("user/leaderboard");
  const top3 = users.slice(0, 3);
  const others = users.slice(3, 11);
  const userToken =
    localStorage.getItem("token") ||
    sessionStorage.getItem("token") ||
    undefined;

  const currentUser = useFetchWithToken("user/me", "GET", userToken);
  const myRank =
    users.findIndex((usr: any) => usr.id === currentUser.data?.id) + 1;

  if (loading) return <p>Chargement...</p>;
  return (
    <section className="leaderboard">
      <div className="leaderboard-top">
        <h1>Classement</h1>
        <p>Les meilleurs hackers de la communauté CyberLearn.</p>
        <div className="leaderboard-podium">
          <div className="podium-item podium-second">
            <span className="rank">2</span>
            <span className="gamertag">{top3[1]?.gamertag}</span>
            <span className="points">{top3[1]?.pointEarn} pts</span>
          </div>
          <div className="podium-item podium-first">
            <span className="rank">1</span>
            <span className="gamertag">{top3[0]?.gamertag}</span>
            <span className="points">{top3[0]?.pointEarn} pts</span>
          </div>
          <div className="podium-item podium-third">
            <span className="rank">3</span>
            <span className="gamertag">{top3[2]?.gamertag}</span>
            <span className="points">{top3[2]?.pointEarn} pts</span>
          </div>
        </div>
        <div className="leaderboard-ranklist">
          <table>
            <thead>
              <tr>
                <th>Rank</th>
                <th>Joueur</th>
                <th>Modules</th>
                <th>Points</th>
              </tr>
            </thead>
            <tbody>
              {others.map((usr: any, index: number) => {
                return (
                  <tr key={usr.id}>
                    <td>{`#${index + 4}`}</td>
                    <td>{usr.gamertag}</td>
                    <td>{usr.userModules.length}</td>
                    <td>{usr.pointEarn}</td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
        <div className="leaderboard-myrank">
          {userToken && currentUser.data && (
            <table>
              <tbody>
                <tr>
                  <td>#{myRank}</td>
                  <td>{currentUser.data.gamertag}</td>
                  <td>{currentUser.data.userModules?.length}</td>
                  <td>{currentUser.data.pointEarn} pts</td>
                </tr>
              </tbody>
            </table>
          )}
        </div>
      </div>
    </section>
  );
}
