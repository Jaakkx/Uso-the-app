import React from 'react';
import logo from './logo.svg';
import Button from './components/Button';
import './App.css';

export type AppProps = {

}

class App extends React.Component<AppProps>{
  render() {
      return(
        <Button />
      );
  }
}

export default App;
