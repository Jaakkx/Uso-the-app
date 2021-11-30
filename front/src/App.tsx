import React from "react";
import logo from "./logo.svg";
import "./App.css";
import Page from "./components/Page";
import { Utilisateur } from "./decl";
import Login from "./components/Login";
// import 'antd/dist/antd.css';

export type AppState = {
  user: Utilisateur | undefined;
};

class App extends React.Component<{}, AppState> {
  state = {
    pages: [],
    user: undefined,
  };

  render() {
    const { pages, user } = this.state;
    console.log('yousk2');
    return (
      <div className="App">
        <Login
          onSuccess={(user) => {
            this.setState({ user: user });
          }}
        />
      </div>
    );
  }
}

export default App;