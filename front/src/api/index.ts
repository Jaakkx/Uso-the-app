import axios from 'axios';
import { Utilisateur } from '../decl';

export const postLogin = async (user: Utilisateur): Promise<Utilisateur> => {
    try{
        const res = await axios.post(
            `${process.env.REACT_APP_BASE_URL}/login`,
            user
        );
        return res.data;
    } catch (error){
        throw new Error("An error occured saving the user");
    }
}