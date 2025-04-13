import { PlayerProfile } from './Player/Profile';
import { PlayerSchedule } from './Player/Schedule';
import { PlayerPerformance } from './Player/Performance';
import { PlayerPayments } from './Player/Payments';
import { PlayerMedia } from './Player/Media';

export default function PlayerDashboard() {
  return (
    <div className="p-6 space-y-8">
      <h1 className="text-3xl font-bold mb-6 border-b pb-2">Player Dashboard</h1>
      
      <section>
        <PlayerProfile />
      </section>

      <section>
        <PlayerSchedule />
      </section>

      <section>
        <PlayerPerformance />
      </section>

      <section>
        <PlayerPayments />
      </section>

      <section>
        <PlayerMedia />
      </section>
    </div>
  );
}
