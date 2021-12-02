import React from "react";
import logo from "./logo.svg";
import "./App.css";
import OsuPage from "./components/OsuPage";
import Banner from "./components/Banner";
// import 'antd/dist/antd.css';

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
        <OsuPage />
      </div>
    );
  }
}

export default App;