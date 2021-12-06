import React from "react";
import logo from "./logo.svg";
import "./App.css";
import Banner from "./components/Banner";
import PlaylistPage from "./components/PlaylistPage";
import { Link } from "react-router-dom";
import ConnexionPage from "./components/ConnexionPage";

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
        <ConnexionPage />
        {/* <a href="http://localhost:8081/oauth">Connexion à Spotify</a> */}
      </div>
    );
  }
}

export default App;