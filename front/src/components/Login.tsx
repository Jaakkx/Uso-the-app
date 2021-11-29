import React from "react";
import { User } from "../decl";
import { postLogin } from "../api";

export type LoginProps = {
  onSuccess: (user: User) => void;
};

export type LoginState = {
  email: string;
  isModalVisible: boolean;
};

class Login extends React.Component<LoginProps, LoginState> {
  state = {
    email: "",
    isModalVisible: true,
  };

  handleFormSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const { email } = this.state;

    try {
      const loggedUser = await postLogin({ email });
      this.props.onSuccess(loggedUser);
      this.setState({ isModalVisible: false });
    } catch (error) {
      alert(error);
    }
  };

  render() {
    const { email, isModalVisible } = this.state;

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