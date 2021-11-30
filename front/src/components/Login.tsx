import React from "react";
import { Utilisateur } from "../decl";
import { postLogin } from "../api";

export type LoginProps = {
  onSuccess: (user: Utilisateur) => void;
};

export type LoginState = {
  email: string;
};

class Login extends React.Component<LoginProps, LoginState> {
  state = {
    email: "",
  };

  handleFormSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const { email } = this.state;

    try {
      const loggedUser = await postLogin({
      email,
      spotifyToken: "",
      osuToken: "",
      playlistsLinks: []
    });
      this.props.onSuccess(loggedUser);
    } catch (error) {
      alert(error);
    }
  };

  render() {
    const { email } = this.state;

    return (
        <form onSubmit={this.handleFormSubmit}>
          <input
            type="email"
            placeholder="E-mail"
            value={email}
            onChange={(event) => this.setState({ email: event.target.value })}
          />

          <input type="submit" value="Envoyer" />
        </form>
    );
  }
}

export default Login;