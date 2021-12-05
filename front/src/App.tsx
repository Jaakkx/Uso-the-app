import React from "react";
import logo from "./logo.svg";
import "./App.css";
import Banner from "./components/Banner";
import PlaylistPage from "./components/PlaylistPage";
import { Link } from "react-router-dom";

export type AppState = {
  searchName: string | undefined;
};

class App extends React.Component<{}, AppState> {
  state = {
    searchName: undefined,
  };

  render() {
    const { searchName } = this.state;
    return (
      <div className="App">
        <Banner />
        {/* <Link to="/playlist-creator">Connexion à Spotify</Link> */}
        <a href="http://uso-api.jael-beining.fr/oauth">Connexion à Spotify</a>
        {/* <PlaylistPage /> */}
      </div>
    );
  }
}

export default App;