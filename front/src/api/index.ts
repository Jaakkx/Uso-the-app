import axios from 'axios';
import { OsuUser } from '../decl/osuUser.decl';

export const getOsuPseudo = async (user: OsuUser): Promise<OsuUser>=> {
    try{
        const res = await axios.post(
            `${process.env.REACT_APP_BASE_URL}/pseudo`,
            user
        )
        return res.data;
    } catch(error) {
        throw new Error("Ca marche pas frerot");
    }
}; 

